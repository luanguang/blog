selenium+chromedriver启动页面，等待一段时间，页面渲染好，可以采取数据，但是有的网页并没有把a标签展示出来。      
而是将链接绑定在点击事件上，他用的是异步请求，但是请求需要签名，签名又无法破解的时候。可以对页面返回的数据进行挖掘。        
也就是每次页面加载完成的时候，执行一段JS拿到数据。      
selenium也自带的一个`execute_script`方法
```python
self.browser = webdriver.Chrome(chrome_options=chrome_options)
data = self.browser.execute_script("""
return $('#itemBox').$$instance.props.children.props.data
""")
```
不过这个时候会报错
```
$ is not defined
```
目前很多H5的页面，前端开发的框架如果使用的是vue，用$就不行，所以此方法行不通。看了一下自己爬取网页的路由，的确是h5开头，那就用原生JS写好了。
```python
data = self.browser.execute_script('return document.getElementById("itemBox").$$instance.props.children.props.data')
```
这次执行成功了。

#### selenium自带的find方法抛出异常
查找页面的时候，想用元素是否存在作为判断来执行判断语句。结果selenium自带的所有find_element_*方法都是抛出相应的异常，而不是返回一个空或者None。      
而response的xpath之类的方法却是返回一个空或者None。在这里浪费了一点时间。       
解决办法也有，要么用原生JS执行查找返回。要么接受异常返回。


