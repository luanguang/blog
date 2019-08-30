```bash
utf8_file='/tmp/classroom_data_export/'`date -d today +"%Y_%m_%d"`
gbk_file='/tmp/classroom_data_export/'`date -d today +"%Y_%m_%d"`'/data_gbk'
mkdir -p $utf8_file
mkdir -p $gbk_file
mysql -uroot -proot -e 'use tencent;
set names gbk;
SELECT id AS "userId", mobile 
FROM user_profile WHERE id IN (
    SELECT userId 
    FROM classroom_member WHERE classroomId IN (
        SELECT id 
        FROM classroom WHERE type = "social"
    ) AND role = "|student|"
);
' >  "$utf8_file"'/data'.xls;
iconv -s -c -f UTF8 -t GBK $utf8_file'/data'.xls > $gbk_file'/data'.xls
zip -r /tmp/classroom_data_export/data.zip $utf8_file

```
第一次写业务相关的bash。