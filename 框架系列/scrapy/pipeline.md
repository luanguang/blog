![流程图](./scrapy%E6%B5%81%E7%A8%8B%E5%9B%BE.png)

### 最后一步item pipeline
作为scrapy框架爬取数据的最后一步，pipeline的主要功能就是对爬取下来的结果进行处理以及保存。
-  清理 HTML 数据。 
-  验证爬取数据， 检查爬取字段。 
-  查重并丢弃重复内容。 
-  将爬取结果保存到数据库。

我们自定义了Item pipeline之后，这个类有
- open_spider(self, spider)
>open_spider（）方法是在 Spider 开启的时候被向动调用的
- close_spider(self, spider)
>close_spider（）方法是在 Spider 关闭的时候自动调用的
- from_crawler(cls, crawler) #这个方法很重要，要重点介绍一下
>from_crawler（）方法是一个类方法，用@classmethod 标识，是一种依赖注入的方式。 它的参数是 crawler，通过 crawler 对象，我们可以拿到 Scrapy 的所有核心组件，如全局配置的每个信息，然后创 建一个 Pipeline 实例。 参数 cls 就是 Class，最后返回一个 Class 实例。 
- process_item(self, item, spider) #这个方法必须要实现！ 
>我们可以进行数据处理或者将数据写入到数据库等操作。 它必须返回 Item 类型的值或者 抛出一个 DropItem 异常。

比如我们要对返回的item进行一些处理，处理返回字段的长度之类的可以这样做
```python
class TextPipeline(object):
    def __init__(self):
        self.limit = 50

    def process_item(self, item, spider):
        if item['text']:
            if len(item['text']) > self.limit:
                item['text'] = item['text'][0:self.limit].rstrip() + '...'
            return item
        else:
            return DropItem('Missing Text')
```

**spider中from_crawler方法调用是在spider类实例化以后，而在中间件，管道及拓展中，from_crawler方法调用是在相应的类实例化以前**

我们要把item存储到mongoDB当中去，可以这样做
```python
class MongoPipeline(object):
    def __init__(self, mongo_uri, mongo_db):
        self.mongo_uri = mongo_uri
        self.mongo_db = mongo_db

    @classmethod
    def from_crawler(cls, crawler):
        return cls(
            mongo_uri=crawler.settings.get('MONGO_URI'),
            mongo_db=crawler.settings.get('MONGO_DB')
        )

    def open_spider(self, spider):
        self.client = pymongo.MongoClient(self.mongo_uri)
        self.db = self.client[self.mongo_db]

    def process_item(self, item, spider):
        name = item.__class__.__name__
        self.db[name].insert(dict(item))
        return item

    def close_spider(self, spider):
        self.client.close()
```
在`init`方法中定义的时候，传入的参数，他是根据from_crawler传回的参数顺序来的。之前踩了一个小坑，花了大概两个小时。我在`init`方法中定义了三个参数，但是from_crawler只返回了两个，导致我怎么也取不到正确的参数，真的惨。

做完这些，我们还要到`settings.py`文件当中对这些参数进行设置。
```python
ITEM_PIPELINES = {
   'tutorial.pipelines.TextPipeline': 300,
   'tutorial.pipelines.MongoPipeline': 400,
}
MONGO_URI='localhost'
MONGO_DB='tutorial'
```
ITEM_PIPELINES里的管道对应的数值越小，执行的顺序越靠前，也就是我们处理文本要在存储数据之前执行。