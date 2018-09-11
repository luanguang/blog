import pymysql
import requests
import re
import time
import json
import random
from pyquery import PyQuery as pq
from urllib.parse import urlencode

user_agent_list = [
     "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1",
     "Mozilla/5.0 (X11; CrOS i686 2268.111.0) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",
     "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6",
     "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6",
     "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/19.77.34.5 Safari/537.1",
     "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5",
     "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5",
     "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
     "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
     "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_0) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3",
     "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.0 Safari/536.3",
     "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24",
     "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24"
    ]

def getip(page):
    datas = []
    user_agent = random.choice(user_agent_list)
    headers = {'User-Agent': user_agent}
    url = 'https://www.kuaidaili.com/free/inha/' + str(page) + '/'
    response = requests.get(url, headers=headers)
    try:
        if response.status_code == 200:
            print('连接成功')
            doc = pq(response.text)
            i = 1
            data = {}
            for each in doc('td').items():
                if each.attr['data-title'] == 'IP':
                    data['ip'] = each.text()
                if each.attr['data-title'] == 'PORT':
                    data['port'] = each.text()
                if each.attr['data-title'] == u'类型':
                    data['type'] = each.text()
                if each.attr['data-title'] == u'位置':
                    data['address'] = each.text()
                i += 1
                if i % 8 == 0:
                    i = 1
                    datas.append(data)
                    data = {}
    except requests.ConnectionError:
        print('连接失败')
    return datas

def save_proxy(datas):
    for data in datas:
        db = pymysql.connect(host='127.0.0.1', user='root', passwd='root', port=3306, db='pyspider-test')
        cursor = db.cursor()

        sql = 'INSERT INTO proxies(ip,port,type,address) VALUES(%s,%s,%s,%s)'
        try:
            if cursor.execute(sql, (data['ip'],data['port'],data['type'],data['address'])):
                print('success')
                db.commit()
        except:
            print('failed')
            db.rollback()
        db.close()

if __name__ == '__main__':
    group = range(1, 50)
    for page in group:
        time.sleep(1.5)
        save_proxy(getip(page))

        
    
    




