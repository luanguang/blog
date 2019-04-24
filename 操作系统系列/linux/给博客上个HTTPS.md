第一步
```
# 下载 Certbot 客户端
$ wget https://dl.eff.org/certbot-auto

# 设为可执行权限
$ chmod a+x certbot-auto
```
第二步 将XXX.com改成自己的域名
`./certbot-auto certonly  -d "*.xxx.com" --manual --preferred-challenges dns-01  --server https://acme-v02.api.letsencrypt.org/directory`

第三步
[![](https://www.guangsky.com/uploads/article/20190214/5c64ddccc14a6.png)](https://)
输入自己邮箱之后，他会发送一份邮件给你确认，确认完毕，到`Before continuing`这里先不要立刻点击确认。到域名解析里添加一条txt的解析，值是添加_acme-chanllenge.你的域名。下面的一串添加到记录值里。然后添加成功再按回车继续。

第四步
到nginx配置文件里添加
```
listen 443 http2 ssl;
    ssl on;
    ssl_certificate /etc/letsencrypt/live/xxx.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/xxx.com/privkey.pem;
```

第五步 想要直接跳转到https，添加
```
server {
       listen 80;
       server_name XXXX;
       location / {
           return 301 https://$server_name$request_uri;
       }
  }
```



[给HTTPS续期，要天数小于30天才可以。](https://www.jianshu.com/p/d318321b93a1)