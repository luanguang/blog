### 抓取页面链接进行递归爬取
平日里爬取有分页的网页，都是直接一步步yield下去，最后返回一个item给pipeline处理。
例子：
```python
#没有start_requests方法设置起始路由，会读取start_urls参数做起始路由爬取
def start_requests(self):
  url = '起始url'
  yield Request(url=url, callback=self.parse)
 
def parse(self, Response):
    item = item()
    item['content'] = response.xpath('//body').extract_first()
    yield item
```
但是递归爬取是用来对付一些没有分页的网站，比如说百度百科，wiki之类的。那么就要换一种写法
格式：
```python
def parse(self, Response):
  item = item()
  item['content'] = response.xpath('//body').extract_first()
  yield item
  url = response.xpath('xxx').extract_first()
  yield Request(url=url, callback=self.parse)
```
>关键点还是理解yield，他不像return直接返回不进行接下来的操作。而是会返回，并且还能继续接下来的操作。

### xpath的运用
想要爬取某个节点的文本很容易实现，直接在后面加个`text()`就可以了。
```python
response.xpath('//h3[@class="nickname"]/a/text()').extract_first()
```
想要爬取复数个`class`值的`href`属性值
```python
response.xpath('//div[contains(@class, "note-item") and contains(@class, "note")]//a/@href').extract_first()
```
想要爬取某个节点里面的所有东西，包括html
```python
response.xpath('//div[@class="content"]//div[@class="content"]').extract_first()
```