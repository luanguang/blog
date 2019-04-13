为了加快爬取数据的获取，最终还是走上了爬虫的分布式集群之路。        
![scrapy_reids流程图](./scrapy_redis.jpg)

## 安装
```python
pip install scrapy_redis
```

往`settings.py`里添加`redis`配置
```python
REDIS_HOST = localhost
REDIS_PORT = 6379
REDIS_ENCODING = "utf-8"
```
接下来我们得考虑一下使用情况了。
## 使用     
`scrapy_redis`实现了调度器和去重的其他方式，根据需求的不同，可以有不同的使用情况。
1. 只使用去重功能  (没用过)     
```python
#settings添加配置
DUPEFILTER_CLASS = "scrapy_redis.dupefilter.RFPDupeFilter"
```

2. 只实现调度器功能 (没用过)
```python
#settings添加配置

 # 有引擎来执行：自定义调度器
SCHEDULER = "scrapy_redis.scheduler.Scheduler"
#去重规则对应处理的类
DUPEFILTER_DEBUG = False
# 是否在关闭时候保留原来的调度器和去重记录，True=保留，False=清空
SCHEDULER_PERSIST = True  


#SCHEDULER_QUEUE_CLASS = 'scrapy_redis.queue.LifoQueue'  # 默认使用优先级队列（默认广度优先），其他：PriorityQueue（有序集合），FifoQueue（列表）、LifoQueue（列表）
#SCHEDULER_QUEUE_KEY = '%(spider)s:requests'  # 调度器中请求存放在redis中的key
#SCHEDULER_SERIALIZER = "scrapy_redis.picklecompat"  # 对保存到redis中的数据进行序列化，默认使用pickle
#SCHEDULER_FLUSH_ON_START = False  # 是否在开始之前清空 调度器和去重记录，True=清空，False=不清空
# SCHEDULER_IDLE_BEFORE_CLOSE = 10  # 去调度器中获取数据时，如果为空，最多等待时间（最后没数据，未获取到）。
#SCHEDULER_DUPEFILTER_KEY = '%(spider)s:dupefilter'  # 去重规则，在redis中保存时对应的key  chouti:dupefilter
#SCHEDULER_DUPEFILTER_CLASS = 'scrapy.dupefilter.RFPDupeFilter'  # 去重规则对应处理的类
```

3. 调度器+去重 (用过)
```python
#settings配置添加
SCHEDULER = "scrapy_redis.scheduler.Scheduler"
SCHEDULER_DUPEFILTER_CLASS = 'scrapy_redis.dupefilter.RFPDupeFilter'
SCHEDULER_PERSIST = True
#广度优先1 深度优先-1
DEPTH_PRIORITY = 1
```

4. 将数据存到redis当中  
内存很贵的，别闹了，到时候数据在redis当中还要写脚本存到想要的地方，多累哦！直接上mongoDB啊！

5. 调度器+去重+推start_urls到redis当中
```python
#settings配置添加
REDIS_START_URLS_BATCH_SIZE = 1
# REDIS_START_URLS_AS_SET = True # 把起始url放到redis的集合
REDIS_START_URLS_AS_SET = False # 把起始url放到redis的列表

#spider修改
from scrapy_redis.spiders import RedisSpider

class BaiduSpider(RedisSpider):
name = 'baidu'
allowed_domains = ['baidu.com']

def parse(self, response):
    # 随着深度的增加、优先级一直在递减
    print(response)
```


