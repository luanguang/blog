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
