### 魔术方法的调用
```
<?php
class A {
    public $div = 0;
}
$a = new A();
$a->bar = 2;
echo $a->bar;
#2
```
`能够直接访问未定义变量bar是因为调用了魔术方法__set`
```
function __set($name, $value) {
    return $this->data[$name] = $value;
}
```
>可以定义的就先定义，这也有一点小性能可以提升。

### 抽象类
抽象类的定义：定义为抽象的类不能被实例化。任何一个类，__如果它里面至少有一个方法是被声明为抽象的，那么这个类就必须被声明为抽象的__。被定义为抽象的方法只是声明了其调用方式（参数），不能定义其具体的功能实现。__继承抽象类，必须实现抽象方法__
>关键字 abstract

### static 静态延迟绑定

>转发调用
>>self::, parent::, static::,  forward_static_call()这几个方式都是转发调用

>非转发调用
>>foo::bar(), foo->bar()这样子属于非转发调用

`后期静态绑定工作原理是存储了在上一个“非转发调用”（non-forwarding call）的类名。`
>PHP面向对象中new self( )和 new static( ) 的区别
>>在PHP中 self指向定义了当前被调用方法的类， static指向调用当前静态方法的类。

```php
<?php
class A {
    public static function foo() {
        static::who();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}

class B extends A {
    public static function test() {
        A::foo();
        parent::foo();
        self::foo();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}
class C extends B {
    public static function who() {
        echo __CLASS__."\n";
    }
}

C::test();
#A
#C
#C
```






