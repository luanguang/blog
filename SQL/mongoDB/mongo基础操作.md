因为scrapy存储数据到mysql当中时会出现截断现象，谷歌一天也找不到任何解决办法，就算异步插入还是会有这个现象，最终还是放弃了，选择用mongoDB。      
mongo顺序是`库 -> 集合 -> 表`

python示例
```python
import pymongo

myclient = pymongo.MongoClient('mongodb://localhost:27017/')
#连接库
mydb = myclient['taobao']
#连接集合内的表
mycol = mydb['taobao']
#查找该表内的所有数据
datas = mycol.find({})
#查找单挑数据
data = mycol.find_one({})
#条件查询 返回的是一个对象，你可以通过循环输出。
data = mycol.find({"title": "第一条", "sellerId": "123456"})


#mongo不像mysql需要先建表才能插数据。当你插一条数据，发现没有这个表的时候，他会自动建立。
#mongo插入的数据也没有规格的限制，随意插入
data = mycol.insert_one(
{
    "_id" : ObjectId("5c7f69436703ae33403f4585"),
    "url" : "https://www.xiaohongshu.com/discovery/item/5c727295000000000d0157c9",
    "article_id" : "5a1ec04d4eacab077d798d07",
    "author" : "大雷芸",
    "title" : "年后减肥❗️出来吃总是要还的✨睡前7个动作/躺着瘦腰瘦！ 躺床上也能瘦腰！",
    "image" : [ 
        "https://ci.xiaohongshu.com/fedcce37-4c9f-50b6-a8e5-dd7057d15852?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/a25f1249-b09f-3d98-9172-0794bd64c0d6?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/cebf4706-1a8c-5cf9-8059-fe3ca567bc05?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/06dac7e2-a78a-5d3a-a669-80f081e85c7b?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/d8d6f385-62d8-5de6-a210-5749ce1466e0?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/1395a811-c003-308a-8d19-d92c9c2af521?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/fb7c721c-cdd0-5e64-bb2b-f4d321b611e6?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/38e6f04f-9c1a-5cf7-82aa-c08eedc3dc95?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center", 
        "https://ci.xiaohongshu.com/77a47811-c768-5df9-9ea1-8dcf30b8ddfe?imageMogr2/format/jpg/quality/92/auto-orient/strip/crop/450x300/gravity/center"
    ],
    "content" : "年后减肥❗️出来吃总是要还的✨睡前7个动作/躺着瘦腰瘦！\n躺床上也能瘦腰！拜托你不要再胖下去❗️几个动作变身小腰精❗️还能提升气质❗️塑造形体\n教你躺瘦的7️⃣个方法：\n✅✅图3-图9 简单的7️⃣个动作\n✨\n（动作详解，图片上写得很详细，有任何疑问欢迎随时问我～[喝奶茶R]）\n1.难度不高，易坚持！每天都可以完成！\n2.成效快，坚持一周下来，肉眼可见肚子上的肉变得紧致，然后一圈一圈褪去游泳圈。\n3.没有设备要求、场地要求。在床上就可以做（可以拉着男朋友一起做，增加情趣[偷笑R]）\n✨科普：运动时一定要穿运动内衣。\n以前不懂事，经常跑步啊运动啊，但也不懂得穿运动内衣[失望R]后来健身教练朋友告诉我，不穿专业的运动内衣hin容易导致胸部下垂！\n而专业的专业内衣会紧紧固住你的胸，塑造一个良好的胸型～ 而且避免下垂哦～\n〰️\n✨我是活生生一个胖子的逆袭，我从116斤到88斤！从一个极度不自信的胖子到现在经常被夸「你好瘦」，有多开心不用说了！[偷笑R]",
    "time" : "2019-02-24 18:31",
    "category" : [ 
        "", 
        "健身", 
        "运动健身"
    ],
    "keyword" : "运动内衣 运动"
})
#假如是多条那个就用insert_many

#mongo的数据备份与恢复
#在bin目录下打开命令行
#进行备份
>mongodump -h dbhost -d dbname -o dbdirectory
#一般会把数据备份在data/dump目录下，然后自动创建一个dbname的文件夹里面保存了备份的数据

#数据恢复
>mongorestore -h <hostname><:port> -d dbname <path>
#默认是localhost:27017
#文件路径就是data/dump/dbname
```

to be continue……