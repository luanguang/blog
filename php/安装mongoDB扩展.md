1. 通过phpinfo()查看，打印出php版本信息，查找Thread Safety选项。Thread Safety：如果是enable，则是Thread Safe（线程安全）版本；否则，就是None Thread Safe（非线程安全）版本。
2. [下载地址](https://pecl.php.net/package/mongodb),点击DLL。X86的意思是32位，X64的意思是64位。现在基本都是64位了。
3. 修改`php.ini`文件，添加
```
extension=php_mongodb.dll
```
4. 重启服务。打开phpinfo()，查看是否安装成功。

>因为下载错了版本，一直报错找不到扩展，然后各种排查，最后发现是下载了线程安全版本……一个小时没了