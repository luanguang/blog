有一个项目是将自己的后台嵌套到其他人的后台当中，使用到了iframe。原本使用起来一切正常，直到chrome浏览器更新了samesite策略。    
有关samesite的可以查看 http://www.ruanyifeng.com/blog/2019/09/cookie-samesite.html    
项目使用的是symfony框架，版本是2.8。使用的php版本是7.1。    
原本想通过设置这个属性来解决，很不幸，symfony和php的版本都不支持设置这个值。      
php7.3才开始可以用setcookie方法进行设置samesite这个值。   
无奈之下原本准备升级项目版本和语言版本。但是这个工作量实在太过庞大，升级两三天放弃了。    
cookie也是通过浏览器头文件传送的，那么直接写入头文件，一样能解决。    
遂使用php自带的header方法进行修改。   
万幸的是，项目当中对于sessionId是存储在数据库当中，所以存储取都比较方便。
```php
header('Set-Cookie:key4=value4; expires=Mon, 28-Nov-2016 15:32:16 GMT; Max-Age=86400; path=/; domain=' . $_SERVER['HTTP_HOST'].';SameSite=None;Secure;');
```
注意要HTTPS才有效。以及一部分浏览器比如旧版QQ浏览器是不支持这个参数的，所以还要做一下判断。