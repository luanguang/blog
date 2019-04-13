laradock环境，用命令行查询数据库的时候，发现返回的所有中文都是`？？？`。虽然有可视化工具可以查看，但是偶尔会用到命令行来查询，那就只好去解决这个事情咯。    
这个是显示问题，无法显示的可能性基本就是跟字符集相关了。于是查看一下mysql的字符集状态。
```sql
mysql>status;

mysql  Ver 14.14 Distrib 5.7.18, for Linux (x86_64) using  EditLine wrapper

Connection id:          3
Current database:
Current user:           root@localhost
SSL:                    Not in use
Current pager:          stdout
Using outfile:          ''
Using delimiter:        ;
Server version:         5.7.18 MySQL Community Server (GPL)
Protocol version:       10
Connection:             Localhost via UNIX socket
Server characterset:    latin1
Db     characterset:    latin1
Client characterset:    latin1
Conn.  characterset:    latin1
```
发现所有的characterset都是latin1。这明显不是我想要的。这种一般来说可以通过一个个修改，就能完成，不过下次启动的时候，他又会变回原来的样子。那么只能在config文件里直接设置了啊。      
由于环境是laradock，跟普通的mysql修改又有些不同。我们得到mysql/my.cnf里进行修改。
```
添加
[client]
default-character-set=utf8mb4

[mysqld]
#在mysqld当中添加以下三条，原有的不需要修改
default-storage-engine=INNODB
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci
```
添加完成以后我们需要重新build一下。
```
docker-compose build mysql
```
然后看到
```
 ---> 49ce53ec085e
Step 7/9 : ADD my.cnf /etc/mysql/conf.d/my.cnf
```
其他配置没有修改，所以会有`Using cache`，而my.cnf修改过了，所以重新添加。打开命令行查询，这次中文是正常显示没有错了。

关于mysql的utf8和utf8mb4其实还是有很大的区别的。utf8mb4算是utf8的升级版，包含更多。

