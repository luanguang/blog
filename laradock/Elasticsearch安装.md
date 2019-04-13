>每次装新东西，都是对docker的一种学习。
### elasticsearch安装
`laradock`里面已经自带了`elasticsearch`（下面简称`ES`），只要运行一下`docker-compose up -d elasticsearch`就OK了。不过`ES`默认不支持中文来着，那只能通过安装其他插件来使用了。      
[elasticsearch-analysis-ik](https://github.com/medcl/elasticsearch-analysis-ik)这是要用到的插件，打开看了一下版本的问题。       
最新的`laradock`的`ES`文件支持的是6.6.0版本。
```
FROM docker.elastic.co/elasticsearch/elasticsearch:6.6.0

EXPOSE 9200 9300
```
于是找相对应的版本插件。    
在`Dockerfile`当中进行修改
```
FROM docker.elastic.co/elasticsearch/elasticsearch:6.6.0
RUN ./bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v6.6.0/elasticsearch-analysis-ik-6.6.0.zip

EXPOSE 9200 9300
```
运行一下`docker-compose up -d elasticsearch`，等待下载完成，然后检查是否安装完成。
```bash
docker-compose exec elasticsearch bash
curl 127.0.0.1:9200
#出现
{
  "name" : "z2cytIn",
  "cluster_name" : "docker-cluster",
  "cluster_uuid" : "5OBLnMSPT0OfBdCqOrUvfg",
  "version" : {
    "number" : "6.3.0",
    "build_flavor" : "default",
    "build_type" : "tar",
    "build_hash" : "424e937",
    "build_date" : "2018-06-11T23:38:03.357887Z",
    "build_snapshot" : false,
    "lucene_version" : "7.3.1",
    "minimum_wire_compatibility_version" : "5.6.0",
    "minimum_index_compatibility_version" : "5.0.0"
  },
  "tagline" : "You Know, for Search"
}
#安装成功
```
弄个项目试试能不能愉快的玩耍。
```
#laravel都5.8啦！好快啊！
composer create-project laravel/laravel elasticsearch --prefer-dist "5.7.*"
```
配置完成，安装两个包，一个用来适配，让你使用`ES`跟`ORM`一样简便。一个就是laravel官方推荐的`scout`扩展。
```php
composer require tamayo/laravel-scout-elastic

composer require laravel/scout ^5.0#这个5.0版本有一个bug，使用的时候会报错Undefined property: Laravel\Scout\Builder::$queryCallback改成5.0.3版本就没问题了。

#config/app.php进行修改
Laravel\Scout\ScoutServiceProvider::class,
ScoutEngines\Elasticsearch\ElasticsearchProvider::class,

#运行 5.5版本以后可以自己选择，不用打完
php artisan vendor:publish
#找到 
Provider: Laravel\Scout\ScoutServiceProvider

#config/scout.php进行添加
'elasticsearch' => [
        'index' => env('ELASTICSEARCH_INDEX', 'laravel'),
        'hosts' => [
            env('ELASTICSEARCH_HOST', 'http://127.0.0.1:9200'),
        ],
 ],

 #.env进行添加配置
SCOUT_DRIVER=elasticsearch  
SCOUT_PREFIX=

ELASTICSEARCH_INDEX=estest  
# elasticsearch服务器地址 laradock是这个
ELASTICSEARCH_HOST=elasticsearch
```
准备工作完成，然后在你要使用搜索的模型里添加
```php
use Laravel\Scout\Searchable;
class Article extends Model
{
    use Searchable;
}
```
设置索引，进行搜索增删改查之类的可以参考https://learnku.com/articles/20311  或者    https://learnku.com/articles/10126/the-configuration-and-use-of-elasticsearch-for-full-text-search  或者文档 https://learnku.com/docs/laravel/5.7/scout/2309        
我也只是刚起步，就不献丑了。



