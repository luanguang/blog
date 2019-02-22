今天把博客布置上线，运行完`composer install`之后，报错`It is unsafe to run Dusk in production.`。
这是因为安装了`dusk`包，当你的`APP_ENV=local、testing`时是本地测试环境，他就是用来检测代码的质量如何，所以不会抛出这个异常。但是当你的`APP_ENV=production`时，也就是你部署到线上了，这个包会暴露很多接口以及程序信息，会给你的程序带来致命的危险。
解决办法也很简单，要么在线上的时候把这个包给删除掉。要么就是在安装这个包的时候，将他放在`composer.json`文件的`require-dev`下面，在线上composer安装完毕，再运行一下
```
composer install --no-dev
```
问题解决。