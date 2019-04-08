## 前提 
环境 windows10+laradock             
目的 想让局域网内的电脑能够访问本地的mysql数据库        
天坑 花了一个上午加一个下午两个小时

## 记录
1. 首先打开windows防火墙规则，然后新建一个入站规则，图片给的是出站规则，换一下，一样操作的。
![新建规则](../images/firewall/outbound-rule.png)

2. 选择docker/docker/resource/vpnkit程序
![vpnkit](../images/firewall/outbound-program.png)
就是在这一步，折腾了这么久。一开始以为放开3306端口就可以，后来怎么也不行，把专用网络关闭了，局域网又可以访问了，一直找不到问题所在。没想到在这里。fuck……

3. 添加端口，laradock绑定多少端口就写多少端口，顺便我把80端口也给开放了。
![ports](../images/firewall/outbound-ports.png)

4. 允许所有
![action](../images/firewall/outbound-action.png)

5. 结束
![finish](../images/firewall/outbound-name.png)

## 结束？不存在的   
即使这样做完之后，局域网依旧无法访问……为什么！！！      

又看了一下，居然入站规则存在对于vpnkit的阻止规则，包括了TCP跟UDP的……我透……他自己怎么就添加进来的……删！！

删完终于可以访问了……
