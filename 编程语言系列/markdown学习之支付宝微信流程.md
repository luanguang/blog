```sequence
Title:支付宝交互流程图
participant 用户
participant 商户客户端
participant 支付宝客户端SDK
participant 支付宝服务端
participant 商户服务端

用户->商户客户端: 1.使用支付宝付款
商户客户端-->>商户服务端: 2.请求商户服务端，获取签名后的订单信息
商户服务端-->>商户客户端: 3.返回签名后的订单信息
商户客户端->支付宝客户端SDK: 4.调用支付接口
支付宝客户端SDK->支付宝服务端: 5.支付请求
支付宝服务端->支付宝服务端: 6.完成支付
支付宝服务端->支付宝客户端SDK: 7.返回同步支付结果
支付宝客户端SDK->商户客户端: 8.接口返回支付结果
商户客户端-->>商户服务端: 9.同步支付结果返回商家服务端，验签，解析支付结果
商户服务端-->>商户客户端: 10.返回最终支付结果
商户客户端->用户: 11.显示支付结果
支付宝服务端-->>商户服务端: 12.异步发送支付通知
商户服务端-->>支付宝服务端: 13.接受响应
```

```sequence
Title:微信支付流程图

participant 用户
participant 微信客户端
participant 商户App客户端
participant 商户后台系统
participant 微信支付系统

用户-->>商户App客户端: 1.打开商户App客户端
商户App客户端->商户App客户端: 2.选择商品下单
商户App客户端->商户后台系统: 3.请求生成支付订单
商户后台系统->微信支付系统: 4.调用统一下单API
微信支付系统->微信支付系统: 生成预付单
微信支付系统->商户后台系统: 5.返回预付单信息（prepay_id）
商户后台系统->商户后台系统: 6.生成带签名的客户端支付信息
商户后台系统->商户App客户端: 7.返回信息（prepay_id,sign等）
用户-->>商户App客户端: 8.用户确认支付
商户App客户端->微信客户端: 9.支付参数通过调用SDK调起微信支付
微信客户端-->>微信支付系统: 10.发起支付请求
微信支付系统->微信支付系统: 验证支付参数、APP支付权限等
微信支付系统-->>微信客户端: 11.返回需要支付授权
用户->微信客户端: 12.用户确认支付、输入密码
微信客户端-->>微信支付系统: 13.提交支付授权
微信支付系统->微信支付系统: 验证授权、完成支付交易
微信支付系统->商户后台系统: 15.异步通知商户支付结果
商户后台系统->商户后台系统: 接收和保存支付通知
商户后台系统->微信支付系统: 16.返回告知已成功接收处理
微信支付系统-->>微信客户端: 14.返回支付结果，发送微信消息提醒
微信客户端->商户App客户端: 将支付状态通过商户APP已实现的回调接口执行回调
商户App客户端->商户后台系统: 后台查询实际支付结果
商户后台系统->微信支付系统: 调用微信查询API查询支付结果
微信支付系统->商户后台系统: 返回支付结果
商户后台系统->商户App客户端: 返回支付结果
商户App客户端-->>用户: 展示支付结果
```

### 异
支付宝在付款之前是不会产生订单，微信在下单时会请求并产生一条订单数据。
支付在支付完成之后，商户才对支付进行校验之类的操作
微信在支付的同时也对其产生的支付信息进行校验

### 同
在支付完成之后，微信与支付都有一个通知商户的操作。

