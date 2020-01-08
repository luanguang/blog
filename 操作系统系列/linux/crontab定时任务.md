编辑定时任务
```
crontab -e

几点几分定时执行
48 15 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 0 3000 1 
53 15 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 3001 3000 2 
58 15 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 6002 3000 3 
03 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 9003 3000 4 
08 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 12004 3000 5 
13 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 15005 3000 6 
18 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 18006 3000 7 
23 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 21007 3000 8 
28 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 24008 3000 9 
33 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 27009 3000 10 
38 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 30010 3000 11 
43 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 33011 3000 12 
48 16 * * * sudo /opt/code/edusoho/app/console util:export-course-user-learn /tmp/export_course_status/ 36012 3000 13 

几点多少时间执行一遍
*/30 18 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 500000 30000 14
*/45 18 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 530000 30000 15
*/1 19 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 560000 30000 16
*/15 19 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 590000 30000 17
*/30 19 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 620000 30000 18
*/45 19 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 650000 30000 19
*/1 20 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 680000 30000 20
*/15 20 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 710000 30000 21
*/30 20 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 740000 30000 22
*/45 20 * * * sudo /opt/code/edusoho/app/console util:export-course-test /tmp/export_20191101/ 770000 30000 23
```
查看
```
crontab -l
```