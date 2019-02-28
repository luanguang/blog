## Ioc容器
IOC容器是laravel的核心也是难点。以下是简单的核心实现方法，实际上laravel要比这个更加复杂。

```php
<?php
interface Visit
{
    public function go();
}

class Leg implements Visit
{
    public function go()
    {
        echo 'walt to Tibet!!!';
    }
}
class Car implements Visit
{
    public function go()
    {
        echo 'go to Tibet by car';
    }
}
class Train implements Visit
{
    public function go()
    {
        echo 'go to Tibet by Train';
    }
}

class Container
{
    protected $bindings = [];

    public function bind($abstract, $concrete = null, $shared = false)
    {   #Closure 判定是否为一个闭包，也就是判定是否为匿名函数
        if (!$concrete instanceof Closure) { 
            $concrete = $this->getClosure($abstract, $concrete);
        }
        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    protected function getClosure($abstract, $concrete)
    {
        return function ($c) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $c->$method($concrete);
        };
    }

    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        return $object;
    }

    public function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    protected function getConcrete($abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }

    public function build($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }
        #检测传入参数的反射类，这里传入Visit，简历了Visit的反射类。
        $reflector = new ReflectionClass($concrete);
        #检测Visit类是否可实例化，明显不行
        if (!$reflector->isInstantiable()) {
            echo $message = "Target [$concrete] is not instantiable.";
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);
        return $reflector->newInstanceArgs($instances);
    }

    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = null;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }

        return (array) $dependencies;
    }

    protected function resolveClass(ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}

class Traveller
{
    protected $trafficTool;

    public function __construct(Visit $trafficTool)
    {
        $this->trafficTool = $trafficTool;
    }

    public function visitTibet()
    {
        $this->trafficTool->go();
    }
}

$app = new Container();
$app->bind('Visit', 'Train');
$app->bind('traveller', 'traveller');
$tra = $app->make('traveller');
$tra->visitTibet();

```
- bind()：将类进行绑定
- singleton()：bind的特例，shared参数为true，也是进行绑定
```php
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Essential\Console\Kernel::class
);
```
- make()：实例化
- build()：构建关系

## 中间件

首先介绍一下装饰模式者
```php
interface Decorator
{
    public function display();
}

class XiaoFang implements Decorator
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function display()
    {
        echo '我是' . $this->name . '我要出门了!!' . '<br>';
    }
}

class Finery implements Decorator
{
    private $component;

    public function __construct(Decorator $component)
    {
        $this->component = $component;
    }

    public function display()
    {
        $this->component->display();
    }
}

class Shoes extends Finery
{
    public function display()
    {
        echo '穿上鞋子' . '<br>';
        parent::display();
    }
}

class Skirt extends Finery
{
    public function display()
    {
        echo '穿上裙子' . '<br>';
        parent::display();
    }
}

class Fire extends Finery
{
    public function display()
    {
        echo '出门前先整理头发';
        parent::display();
        echo '出门后再整理一下头发';
    }
}

$xiaofang = new XiaoFang('小芳');
$shoes = new Shoes($xiaofang);
$skirt = new Skirt($shoes);
$fire = new Fire($skirt);
$fire->display();
```
同一个方法，不同类在该方法之前或者之后执行一部分，为最重的目标进行装饰。    
在laravel当中，这个装饰者模式运用于中间件。


```php
interface Middleware
{
    public static function handle(Closure $next);
}

class VerifyCsrfToken implements Middleware
{
    public static function handle(Closure $next)
    {
        echo '验证 Csrf-Token' . '<br>';
        $next();
    }
}

class ShareErrorsFromSession implements Middleware
{
    public static function handle(Closure $next)
    {
        echo '如果session中有errors变量，则共享它' . '<br>';
        $next();
    }
}

class StartSession implements Middleware
{
    public static function handle(Closure $next)
    {
        echo '开启session,获取数据' . '<br>';
        $next();
        echo '保存数据，关闭session' . '<br>';
    }
}

class AddQueuedCookiesToResponse implements Middleware
{
    public static function handle(Closure $next)
    {
        $next();
        echo '添加下一次请求需要的cookie' . '<br>';
    }
}

class EncryptCookies implements Middleware
{
    public static function handle(Closure $next)
    {
        echo '对输入请求的cookie进行解密' . '<br>';
        $next();
        echo '对输出响应的cookie进行加密' . '<br>';
    }
}

class CheckForMaintenanceMode implements Middleware
{
    public static function handle(Closure $next)
    {
        echo '确定当前程序是否处于维护状态' . '<br>';
        $next();
    }
}

function getSlice()
{
    return function ($stack, $pipe) {
        return function () use ($stack, $pipe) {
            return $pipe::handle($stack);
        };
    };
}

function then()
{
    $pipes = [
        'CheckForMaintenanceMode',
        'EncryptCookies',
        'AddQueuedCookiesToResponse',
        'StartSession',
        'ShareErrorsFromSession',
        'VerifyCsrfToken'
    ];
    $firstSlice = function () {
        echo '请求向路由器传递，返回响应' . '<br>';
    };
    $pipes = array_reverse($pipes);//以相反的顺序返回数组
    var_dump(array_reduce($pipes, getSlice(), $firstSlice));
    //reduce 第一个参数是数组，第二个参数是回调函数，第三个参数是回调函数第一个调用的参数
    call_user_func(array_reduce($pipes, getSlice(), $firstSlice));//第一个参数是回调函数，其他参数是参与回调的值
}
```
输出
```
确定当前程序是否处于维护状态
<br>对输入请求的cookie进行解密
<br>开启session,获取数据
<br>如果session中有errors变量，则共享它
<br>验证 Csrf-Token
<br>请求向路由器传递，返回响应
<br>保存数据，关闭session
<br>添加下一次请求需要的cookie
<br>对输出响应的cookie进行加密
<br>
```
array_reduce当中的匿名函数套了很多层。
```php
object(Closure)#8 (1) {
  ["static"]=>
  array(2) {
    ["stack"]=>
    object(Closure)#7 (1) {
      ["static"]=>
      array(2) {
        ["stack"]=>
        object(Closure)#6 (1) {
          ["static"]=>
          array(2) {
            ["stack"]=>
            object(Closure)#5 (1) {
              ["static"]=>
              array(2) {
                ["stack"]=>
                object(Closure)#4 (1) {
                  ["static"]=>
                  array(2) {
                    ["stack"]=>
                    object(Closure)#3 (1) {
                      ["static"]=>
                      array(2) {
                        ["stack"]=>
                        object(Closure)#1 (0) {
                        }
                        ["pipe"]=>
                        string(15) "VerifyCsrfToken"
                      }
                    }
                    ["pipe"]=>
                    string(22) "ShareErrorsFromSession"
                  }
                }
                ["pipe"]=>
                string(12) "StartSession"
              }
            }
            ["pipe"]=>
            string(26) "AddQueuedCookiesToResponse"
          }
        }
        ["pipe"]=>
        string(14) "EncryptCookies"
      }
    }
    ["pipe"]=>
    string(23) "CheckForMaintenanceMode"
  }
}
```
执行顺序`Che(Enc(Add(Sta(Sha(Ver(function (firstSlice, Verify)))))))`这些是基础的，在laravel当中也能够看到
```php
namespace App\Http;
class Kernel extends HttpKernel {
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            # \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ]
}
```
通过这个装饰者模式学习，当我们想自定义一个中间件的时候，有两种考虑，一种考虑是在请求处理响应生成之前，一种是请求处理响应生成之后。
```php
class CheckRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role == 'sale') {
            abort(403, '权限不足');
        }
        #请求处理响应生成之前
        return $next($request);
        #请求处理响应生成之后
    }
}
```
这设计的相当牛皮啊