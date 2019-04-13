php内置函数只有你想不到，没有他做不到……背完这些内置函数名，都够去考一个四级了……         
`filter_var`这个函数，其实能解决很多平时需要用到正则才能解决的地方的问题。正则效率其实不是很好，有自带还是用自带的比较好。          
- 验证Email
```php
function is_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

var_dump(is_email('fasjklfad'));
var_dump(is_email('321@163.com'));
var_dump(is_email('fsdf@gamil.'));

//输出
// bool(false)
// bool(true)
// bool(false)
```

- 验证Url
```php
function is_url($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }
    return false;
}

var_dump(is_url('www.baidu.com'));
var_dump(is_url('http://www.baidu.com'));
var_dump(is_url('https://www.baidu.com'));
var_dump(is_url('http://www.baidu'));

//输出
// bool(false)
// bool(true)
// bool(true)
// bool(true)
```
其实这个的效果不算特别的好，主要就验证前面的`http`和`https`。不过大千世界，url也是千奇百怪。

- 验证IP
```php
function is_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return true;
    }
    return false;
}

var_dump(is_ip('45.459.78.'));
var_dump(is_ip('127.0.0.1'));
var_dump(is_ip('2001:0db8:85a3:08d3:1319:8a2e:0370:7334'));

//输出
// bool(false)
// bool(true)
// bool(true)

```
能验证IPV4还能验证IPV6，这个效果还是不错的。        
这个内置函数还能进行其他一些操作。
