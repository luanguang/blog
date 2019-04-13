![流程图](./scrapy%E6%B5%81%E7%A8%8B%E5%9B%BE.png)

### 下载中间件
下载中间件是scrapy框架流程的第四第五步，分别处理request和response。要想使用好这东西，要搞懂执行顺序，返回类型之后又进行了什么处理。

- 对于请求的中间件实现 process_request(request, spider)
- 对于处理回复中间件实现process_response(request, response, spider)
- 以及异常处理实现 process_exception(request, exception, spider)

#### process_request(self, request, spider)
> request，是 Request 对象，即被处理的 Request。        
> spider，是 Spdier 对象，即此 Request对应的 Spider。 
- 当返回是 None 时， Scrapy 将继续处理该 Request，接着执行其他 Downloader Middleware 的 process_request（）方法，一直到 Downloader把 Request执行后得到 Response 才结束。 这个过 程其实就是修改 Request 的过程，不同的 Downloader Middleware 按照设置的优先级顺序依次 对 Request进行修改，最后送至 Downloader执行。 

- 当返回为 Response 对象时，更低优先级的 Downloader Middleware 的 process_request（）和 process_exception（）方法就不会被继续调用，每个DownloaderMiddleware 的 process response() 方法转而被依次调用。 调用完毕之后，直接将 Response 对象发送给 Spider来处理。

- 当返回为 Request 对象时，更低优先级的 Downloader Middleware 的 process_request（）方法会 停止执行。 这个 Request 会重新放到调度队列里，其实它就是一个全新的 Request， 等待被调 度。 如果被 Scheduler调度了，那么所有的 Downloader Middleware 的 process request（）方法 会被重新按照顺序执行。 

- 如果 IgnoreRequest 异常抛出， 则所有的 Downloader Middleware 的 process_exception（）方法 会依次执行。 如果没有一个方法处理这个异常，那么 Request 的 errorback（）方法就会回调。 如果该异常还没有被处理，那么它便会被忽略。

#### process_ response (self, request, response， spider)
> request， 是 Request 对象， 即此 Response对应的 Request。     
> response ， 是 Response 对象， 即此被处理的 Response。    
> spider，是 Spider 对象， 即此 Response对应的 Spider。    

-  当返回为 Request 对象时，更低优先级的 Downloader Middleware 的 process_response（）方法 不会继续调用。 该 Request 对象会重新放到调度队列里等待被调度，它相当于一个全新的 Request。 然后，该 Request 会被 process request（）方法顺次处理。 
-  当返回为 Response对象时，更低优先级的 Downloader Middleware 的 process_response（）方法 会继续调用，继续对该 Response 对象进行处理。 
- 如果 IgnoreRequest异常抛向，则 Request 的 errorback（）方法会回调。 如果该异常还没有被处 理，那么它便会被忽略。

#### process_exception(self， request, exception, spider)

> request ，是 Request 对象，即产生异常的 Request。     
> exception，是 Exception 对象，即抛出的异常。  
> spdier，是 Spider 对象，即 Request对应的 Spider。   

当下载处理模块或process_request()抛出一个异常（包括IgnoreRequest异常）时，该方法被调用  

通常返回None,它会一直处理异常


比如我们要加一个`User-Agent`,可以定义一个中间件来处理。
```python
class RandomUserAgentMiddleware():
    def __init__(self):
        self.user_agents = [
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)",
            "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.35; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
            "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
            "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)",
            "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
            "Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.6",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2pre) Gecko/20070215 K-Ninja/2.1.1",
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/20080705 Firefox/3.0 Kapiko/3.0",
            "Mozilla/5.0 (X11; Linux i686; U;) Gecko/20070322 Kazehakase/0.4.5",
            "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20",
            "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; fr) Presto/2.9.168 Version/11.52",
        ]

    def process_request(self, request, spider):
        request.headers['User-Agent'] = random.choice(self.user_agents)

    def process_response(self, request, response, spider):
        response.status = 201
        return response
```
process_response修改了返回的状态码，在spider当中看到的返回状态码就是201了。

当然也可以把User-Agent放到settings.py里面，我们使用from_crawler方法来获取。

**spider中from_crawler方法调用是在spider类实例化以后，而在中间件，管道及拓展中，from_crawler方法调用是在相应的类实例化以前**

```python
def __init__(self, user_agent):
    self.user_agent = user_agent

@classmethod
    def from_crawler(cls, crawler):
        user_agent = random.choice(crawler.settings.getlist('USER_AGENT'))
        return cls(user_agent)
```


采坑记录：有时候我们爬取一些网站，但是因为访问过于频繁，ip被ban了。这时候我们可以通过process_response方法进行判定返回的状态码或者返回内容，假如不是预计的内容我们可以进行修改ip代理。并且返回一个request，让他重新进到爬取的队列里面进行调度。
```python
def process_response(self, request, response, spider):
        if response.status != 200 or response.xpath('//h1/span/text()').extract_first() == '未连接到互联网':
            self.proxy = self.getProxy()
            self.error_count += 1
            return request
        return response
```
不过记住要到spider里面进行设置不去重。因为这个网址已经爬取过了，只不过失败了，要重新爬取。但是他已经被记录下来，不设置的话会报错` DEBUG: Filtered duplicate request: xxxxxxxxxxxxxxxxxx no more duplicates will be shown (see DUPEFILTER_DEBUG to show all duplicates)`

```python
def start_requests(self):
        #到settings.py文件里读取关键词信息
        for keyword in self.settings.get('KEYWORDS'):
            url = self.base_url + quote(keyword)
            yield Request(url=url, callback=self.parse, dont_filter=True)
```
加上`dont_filter=True`就不去重了。

最后在settings.py当中把自定义的中间件加上。
```python
DOWNLOADER_MIDDLEWARES = {
   'scrapyxhs.middlewares.SeleniumMiddleware': 543,
   'scrapy.downloadermiddlewares.useragent.UserAgentMiddleware': None #用来把默认的USER-AGENT中间件关闭
}
```

数字越小，越靠近引擎，数字越大越靠近下载器，所以数字越小的，process_request()优先处理；数字越大的，process_response()优先处理；若需要关闭某个中间件直接设为None即可








