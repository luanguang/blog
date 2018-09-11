import telnetlib
import pymysql

def get_proxy():
    db = pymysql.connect(host='127.0.0.1', user='root', passwd='root', port=3306, db='pyspider-test')
    cursor = db.cursor()
    sql = 'SELECT * FROM proxies'
    try:
        cursor.execute(sql)
        datas = cursor.fetchall()
        return datas
    except:
        print('Error')
    db.close()

def test_ip(datas):
    temps = []
    for data in datas:
        try:
            print('ip: ' + data[1])
            telnetlib.Telnet(data[1], port=data[2], timeout=5)
            temps.append(str(data[0]))
            print('success')
        except:
            print('failed')
    db = pymysql.connect(host='127.0.0.1', user='root', passwd='root', port=3306, db='pyspider-test')
    cursor = db.cursor()
    st = ',' 
    sql = 'DELETE FROM proxies WHERE id NOT IN (' + st.join(temps) + ')'
    try:
        cursor.execute(sql)
        print('success')
        db.commit()
    except:
        print('failed')
        db.rollback()
    db.close()

test_ip(get_proxy())