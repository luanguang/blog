读取mongoDB数据，然后进行循环使用的时候，爬了一会儿，突然报错
```
pymongo.errors.CursorNotFound: cursor id '...' not valid at server.
```
赶忙打开了查看输出日志，然后发现他调用的数据ID连接的网址不存在，当时就想可能网址不存在导致这个报错，不过很快否定了，因为已经做过网址不存在的处理。那么这个是什么问题呢？    
结果居然是连接超时了……      
根据mongoDB的一些教程能看到他会教怎么连接，怎么插入数据怎么查询数据，可是当时有一个疑惑，难道mongoDB都不需要关闭连接的吗？      
因为教程里并没有用到`close`。于是默认的认为mongo不需要关闭。结果在这里踩了个坑。    
接下来就用close把连接关闭呗。结果一旦关闭了，我想用的数据就直接没有了……于是先把数据查出来，然后用一个新的变量将这些数据保存下来，再将连接关闭，然后再使用。

```python
cursor = mycol.find({})
datas = []
for item in cursor:
    datas.append(item['id'])
cursor.close()
```
当然也可以进行设置，让他不超时。
```python
cursor = mycol.find({}, no_cursor_timeout=True)
```

