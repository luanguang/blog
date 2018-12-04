### composer.json
```ruby
"autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/Helpers.php"
        ]
    }
```
根据psr-4的标准`"App\\": "app/"`的意思就是查看`app`文件夹下的类。比如`app/User.php`就是说app下有一个User类。而User类的命名空间写的是`namespace App;`

我们自己写了一些辅助函数，可以通过files标准，每次执行之前都会自动加载。

- composer dump-autoload  在添加新的类和目录映射时更新autoloader

一个简单的laravel框架**MVC**雏形需要`illuminate/routing`和`illuminate/events`来完成路由的设置以及访问路由返回响应。  
`illuminate/database`，添加一个config文件连接数据库，添加model类。  
`illuminate/view`完成视图功能。

```ruby
{
    "require": {
        "illuminate/routing": "^5.7",
        "illuminate/events": "*",
        "illuminate/database": "^5.7",
        "illuminate/view": "^5.7"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

#### 这些包安装完成之后，都要在public目录下的index.php文件里进行注册。注册功能则在服务容器当中进行实现。

