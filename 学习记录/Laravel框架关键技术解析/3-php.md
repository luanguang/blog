## 使用到的php语法
### 1.1 命名空间
```php
<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel; #取别名，方便使用

class Kernel extends HttpKernel
{
    #code...
}
```
这个类完整的名字应该是`App\Http\Kernel`,命名空间的运用要符合PSR的规范。     
可以用`__NAMESPACE__`魔术常量和`namespace`来访问命名空间。
```php
namespace App\Http;
echo __NAMESPACE__; => 'App\Http'

class Kernel
$a = new namespace\Kernel(); => new App\Http\Kernel();
```

### 1.2 类的自动加载
包含文件`include`和`require`，前者报错继续运行，后者报错直接中断。  
```php
function __autoload($class)
{
    require_once($class . '.php');
}
```
`__autoload`只可以定义一次，可以用`spl_autoload_register`，具体实现如下。
```php
public function register($prepend = false) #为true时添加类会到队列之首，而不是队列尾部。
{
    #array(‘对象名’， ‘函数名’)
    spl_autoload_register(array($this, 'loadClass'), true, $prepend);
}
```
调用**$loader->register()**自动类加载函数。最后加载全局文件。
>5.1跟5.5版本有点微小的差距，但是大体顺序都是从public/index.php->bootstrap/autoload.php->vendor/autoload.php->composer/autoload_real.php->实例化Composer\Autoload\ClassLoader添加相关的文件路径配置

### 2. 匿名函数
```php
<?php
$array = [1,2,3,4];
array_walk($array, function ($value) {
    echo $value;
});
#可以用use关键字，引用域外的变量。
```
该语法在laravel底层当中随处可见

### 3. Trait
trait 不能被实例化，引入trait的类，假如方法相同会覆盖父类方法。

简化三元运算 ?: 和 ?? 同样





