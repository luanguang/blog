一个项目，后台是前后端分离，前台是前后端一体，使用laravel 5.7版本。
在后台接口编写完毕之后，跟前端进行对接，报错
```ruby
Access to XMLHttpRequest at 'http://circle-be.fy/admin/session' from origin 'http://localhost:4200' has been blocked by CORS policy: No 'Access-Control-Allow-Origin' header is present on the requested resource.
```
这是laravel的CORS问题导致的，解决办法是安装一个laravel-cors包
>composer require barryvdh/laravel-cors     
>https://github.com/barryvdh/laravel-cors
根据要求安装完毕之后，根据描述把代码添加上去，把config文件publish过去，OK搞定。

前台要跟微信对接，跟微信对接的接口路由记得要在VerifyCsrfToken中进行排除，不然会造成无法对接的情况。