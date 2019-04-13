# Laravel使用mongoDB（laradock环境）
## 安装mongo
1. 安装mongo到workspace容器当中
a) 打开 .env 文件

b) 在Workspace Container下搜索WORKSPACE_INSTALL_MONGO 参数

c) 将其设置为 true

d) 在PHP-FPM容器下搜索PHP_FPM_INSTALL_MONGO 参数

e) 将其设置为 true

2. 重构容器 docker-compose build workspace php-fpm

3. 使用 docker-compose up 命令运行MongoDB Container（mongo）。

`docker-compose up -d mongo`

### 遇坑
然而`docker-compose ps`却发现
```
laradock_mongo_1          docker-entrypoint.sh mongod      Exit 14
```
这种情况下，你可以尝试一下把docker-compose.yml文件里的mongoDB的路径地址改一下
```
### MongoDB ##############################################
    mongo:
      build: ./mongo
      ports:
        - "${MONGODB_PORT}:27017"
      volumes:
        #- ${DATA_PATH_HOST}/mongo:/data/db
        - ${DATA_PATH_HOST}/mongo:/data/mongodb
      networks:
        - backend
```

## Laravel装包
1. 安装推荐包
```
composer require jenssegers/mongodb
```

2. 注册服务，往`config/app.php`内添加
```
Jenssegers\Mongodb\MongodbServiceProvider::class,
```

3. 添加Facades
```
'Mongo'        => Jenssegers\Mongodb\MongodbServiceProvider::class,
'Moloquent'    => Jenssegers\Mongodb\Eloquent\Model::class,
```

4. 修改`config/database.php`
```php
'mongodb' => [
    'driver'   => 'mongodb',
    'host'     => env('MONGO_HOST', 'localhost'),
    'port'     => env('MONGO_PORT', 27017),
    'database' => env('MONGO_DATABASE'),
    'username' => env('MONGO_USERNAME'),
    'password' => env('MONGO_PASSWORD'),
    'options'  => [
        'database' => 'admin' // sets the authentication database required by mongo 3
    ]
],

'default' => env('DB_CONNECTION', 'mongodb'),
```

5. `.env`文件中添加
```php
MONGO_HOST=mongo
MONGO_PORT=27017
MONGO_DATABASE=your-mongo-database
MONGO_USERNAME= //可为空
MONGO_PASSWORD= //可为空
```

## 开始使用
```php
php artisan make:model Models\Taobao //创建一个Taobao模型

<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Taobao extends Eloquent
{
    protected $collection = 'taobao_doc'; //集合名，数据库已经设置过了。默认mongodb启动。
    protected $primaryKey = '_id'; //设置一个主键
    protected $guarded = [];    // 所有字段设置白名单
}
```
`Eloquent`怎么用，现在怎么用。
```php
Route::get('taobao', function () {
    return App\Models\Taobao::where('title', 'test')->get();
});

[
{
"_id": "5c94950696c9d20006485b83",
"itemId": "77777777777",
"path": "www.baidu.com",
"price": 15,
"title": "test"
},
{
"_id": "5c94951c96c9d200070eb272",
"itemId": "77777777777",
"path": "www.baidu.com",
"price": "15.00",
"title": "test"
},
{
"_id": "5c94954d96c9d20008329683",
"itemId": "77777777777",
"path": "www.baidu.com",
"price": "15.00",
"title": "test"
}
]

Route::get('taobao', function () {
    App\Models\Taobao::insert([
        'itemId' => '77777777777',
        'path'   => 'www.baidu.com',
        'price'  => '15.00',
        'title'  => 'test'
    ]);
});

//运行一次，集合多了一条数据。
```


