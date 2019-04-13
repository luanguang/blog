## 连接mongo
本地连接`mongoDB`的时候`host`是`localhost`也可以是`127.0.0.1`。这是因为在`host`文件当中已经映射了`DNS`解析的缘故。
- 127.0.0.1     
回环地址。该地址指电脑本身，主要预留测试本机的TCP/IP协议是否正常。只要使用这个地址发送数据，则数据包不会出现在网络传输过程中。      
也就是说，他只会监听自己，别人访问根本就不理。

想要让其他人能访问你的本地数据库，那么你的机器此时就当成了一台服务器。
- 0.0.0.0       
在服务器中，0.0.0.0指的是本机上的所有IPV4地址，如果一个主机有两个IP地址，192.168.1.1 和 10.1.2.1，并且该主机上的一个服务监听的地址是0.0.0.0,那么通过两个ip地址都能够访问该服务。 

## 开始修改
找到mongo安装的`bin`目录下的`mongod.cfg`，打开进行修改配置。
- dbpath        
也就是你数据存储的地址，这个自己决定，默认也没关系。

- bindIp    
这个原本是`127.0.0.1`，现在修改成`0.0.0.0`，这样他就能监听同局域网内其他电脑的访问了。

## 打开防火墙
不管是windows，还是linux，想要其他人访问，都要把防火墙打开。        

`windows10`为例，点击右下角的安全图标，跳出防火墙，点击高级设置，点击入站规则，新建规则，选择程序，找到`mongod.exe`添加，然后一直下一步。再添加一个特定端口的入站规则，TCP端口27017，也就是mongo访问的端口。
![](https://github.com/luanguang/blog/blob/master/images/firewall/%E5%9B%BE%E6%A0%87.png)

![](https://github.com/luanguang/blog/blob/master/images/firewall/%E7%95%8C%E9%9D%A2.png)

![](https://github.com/luanguang/blog/blob/master/images/firewall/%E6%96%B0%E5%A2%9E.png)

![](https://github.com/luanguang/blog/blob/master/images/firewall/tcp.png)


两个都添加完成之后，我们点击电脑右键管理，打开服务管理，然后查看`mongoDB`是否已经开启，没有开启就开启，然后再让其他人访问，大功告成。设置成自动开启，还解决了开机自启的问题。

![](https://github.com/luanguang/blog/blob/master/images/firewall/%E5%BC%80%E5%90%AF.png)



