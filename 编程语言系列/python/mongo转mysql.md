存数据库的数据一定要转义处理，不然一直报错，让人头昏脑涨。      
php有`addslashes()`       
python用`re.escape()`
`re.escape()`实际上本身是用来处理需要进行正则表达式匹配的字符串中，本身包含正则表达式元字符的情况，而这个函数的处理方法也很简单，就是对字符串中所有的非字母（ASCII letters）、数字（numbers）及下划线（'_'）的字符前都加反斜线\，这样进行转义处理。

```python
import pymysql
import pymongo
import re

myclient = pymongo.MongoClient('mongodb://localhost:27017/')
mydb = myclient['taobao']
mycol = mydb['taobao']
datas = mycol.find({})

taobao_db = pymysql.connect(host='mysql', user='root', passwd='root', port=3306, db='taobao')
show_cursor = taobao_db.cursor()
pics_cursor = taobao_db.cursor()
users_cursor = taobao_db.cursor()
data_id = 1
show_sql = "INSERT INTO `taobao`.`taobao_show` (`seller_id`, `account_id`, `favour_count`, `gmt_create`, `content_id`, `refer_id`, `show_pic`, `title`) VALUES "
pics_sql = "INSERT INTO `taobao`.`taobao_show_pics` (`taobao_show_id`, `path`, `pic_id`) VALUES "
users_sql = "INSERT INTO `taobao`.`taobao_show_users` (`taobao_show_id`, `user_logo`, `user_nick`, `user_url`) VALUES "
for data in datas:
    if data['title'] != "":
        data['title'] = re.escape(data['title'])
    show_sql += "('%s', '%s', %s, '%s', '%s', '%s', '%s', '%s')," % (data['sellerId'], data['accountId'], data['favourCount'], data['gmtCreate'], data['id'], data['referId'], re.escape(data['showPic']), data['title'])
    for pic in data['pics']:
        if 'id' not in pic.keys():
            pic['id'] = 0
        pics_sql += "(%s, '%s', '%s')," % (data_id, pic['path'], pic['id'])
    if data['user']['userUrl'] != None:
        data['user']['userUrl'] = re.escape(data['user']['userUrl'])
    users_sql += "(%s, '%s', '%s', '%s')," % (data_id, re.escape(data['user']['userLogo']), re.escape(data['user']['userNick']), data['user']['userUrl'])
    data_id += 1


show_sql = show_sql[0:-1] + ';'
pics_sql = pics_sql[0:-1] + ';'
users_sql = users_sql[0:-1] + ';'
show_cursor.execute(show_sql)
pics_cursor.execute(pics_sql)
users_cursor.execute(users_sql)
taobao_db.commit()
taobao_db.close()
```     

