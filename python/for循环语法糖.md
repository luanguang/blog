```python
[
(True,
  {'checksum': '2b00042f7481c7b056c4b410d28f33cf',
   'path': 'full/7d97e98f8af710c7e7fe703abc8f639e0ee507c4.jpg',
   'url': 'http://www.example.com/images/product1.jpg'}),
(True,
  {'checksum': 'b9628c4ab9b595f72f280b90c4fd093d',
   'path': 'full/1ca5879492b8fd606df1964ea3c1e2f4520f076f.jpg',
   'url': 'http://www.example.com/images/product2.jpg'})
]
```
使用scrapy图片下载管道的`get_media_requests`方法返回的数据结构元祖。文档中进行操作如下
```python
image_paths = [x['path'] for ok, x in results if ok]
```
这里用到了for循环的语法糖，也就是`列表推导式`。     
列表推导式是一种可以让代码更简洁，并且可以增加可读性和执行效率的方法。不过这个只建议单个循环，多个嵌套循环，还是老老实实多写几行代码，提高可读性，让新人也能看的明白。      
简单的例子
```python
>>> x = [i for i in range(10)]


# 对每个元素求平方
>>> print([i**2 for i in x])
[0, 1, 4, 9, 16, 25, 36, 49, 64, 81]
#等价于
for i in range(10):
    print(i**2)


# 获取其中的偶数
>>> print([i for i in x if i%2==0])
[0, 2, 4, 6, 8]
#等价于
for i in range(10):
    if i%2 == 0:
        print(i)
```

所以文档中这句等价于
```python
image_paths = []
for ok, x in results:
    if ok:
    image_paths.append(x['path'])
```
