# 了解redis

## 一些数据库和缓存服务器的特性与功能
名称 | 类型 | 数据存储选项 | 查询类型 | 附加功能
--- | --- | --- | --- | --- 
Redis | 使用内存存储(in-memory)的非关系数据库 | 字符串、列表、集合、散列表、有序集和 | 每种数据类型都有自己的专属命令，另外还有批量操作（bulk | operation）和不完全（partial）的事务支持 | 发布与订阅，主从复制（master/slave | replication）,持久化，脚本（存储过程）
memchached | 使用内存存储的键值缓存 | 键值之间的映射 | 创建命令、读取命令、更新命令、删除命令以及其他几个命令 | 为提升性能而设的多线程服务器
MySql | 关系数据库 | 每个数据库可以包含多个表，每个表可以包含多个行；可以处理多个表的视图（view）;支持空间（spatial）和第三方扩展 | SELECT/INSERT/UPDATE/DELETE函数/存储过程 | 支持ACID性质（需要使用InnoDB）,主从复制和主主复制（master/master | replication）
PostgreSql | 关系数据库 | 每个数据库可以包含多个表，每个表可以包含多个行；可以处理多个标的视图（view）;支持空间和第三方扩展；支持可定制类型 | SELECT/INSERT/UPDATE/DELETE内置函数/自定义的存储过程 | 支持ACID性质（需要使用InnoDB）,由第三方支持的多主复制
MongoDB | 使用硬盘存储（on-disk）的非关系文档存储 | 每个数据库可以包含多个表，每个表可以包含多个无模式(no-schema)的BSON文档 | 创建命令、读取命令、更新命令、删除命令、条件查询等 | 支持MapReduce操作，主从复制，分片，空间搜索（spatial-index）
>redis是进行内存的操作，内存的运行速度是硬盘运行速度的10000倍，所以是极快的。同时redis也是可以把数据存储到硬盘当中，进行持久化操作，所以不用担心，一旦服务器重启了咋办。        

## redis存储五种数据结构
|结构类型 |	结构存储的值 |	结构的读写能力
| --- | --- | ---
|STRING |	可以是字符串、整数或者浮点数 |	对整个字符串或者字符串的其中一部分执行操作； 对整数和浮点数执行自增（increment）或者自减（decrement）操作
|LIST |	一个链表，链表上的每个节点都包含了一个字符串 |	从链表的两端推入或者弹出元素；根据偏移量对链表进行修剪（trim）； 读取单个或者多个元素；根据值查找或者移除元素
|SET |	包含字符串的无序收集器（unordered  collection），并且被包含的每个字符串都是独一无二、各不相同的 | 添加、获取、移除单个元素；检查一个元素是否存在于集合中； 计算交集、并集、差集；从集合里面随机获取元素
|HASH |	包含键值对的无序散列表 |	添加、获取、移除单个键值对；获取所有键值对
|ZSET | （有序集合） |	字符串成员（member）与浮点数分值（score）之间的有序映射，元素的排列顺序由分值的大小决定	添加、获取、删除单个元素；根据分值范围（range）或者成员来获取元素

>php的数组用的直接是hashmap实现，所以数据结构这块其实很模糊，懂了还好，不懂的话很容易被误导。建议了解不同的数据结构。

### String
就是平常见到的键值对，一个键对应一个值。
```php
<?php
#连接
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
#设置
$redis->set('hello', 'world');
#获取
echo $redis->get('hello');
#删除
$redis->del('hello');
echo $redis->get('hello');

#输出
world
```

### LIST
跟平常用到的数组差不多。这个数据结构索引是数字，只能对值进行操作。数组是有序的。
```php
#值推入列表左端
$redis->lpush('list', 1);
$redis->lpush('list', 'left');
#值推入列表右端
$redis->rpush('list', 'right');
#获取给定范围的所有值
$list = $redis->lrange('list', 0, -1);
print_r($list);
#获取索引的值
echo $redis->lIndex('list', 1);
#删除列表左端值
$redis->lpop('list');
$list = $redis->lrange('list', 0, -1);
print_r($list);
#删除列表右端值
$redis->rpop('list');
$list = $redis->lrange('list', 0, -1);
print_r($list);

#输出
world
Array
(
    [0] => left
    [1] => 1
    [2] => right
)
1
Array
(
    [0] => 1
    [1] => right
)
Array
(
    [0] => 1
)
```

### SET
集合，链表的数据结构，是无序的。

```php
#往set集合添加值
$redis->sAdd('set', 'item1');
$redis->sAdd('set', 'item2');
$redis->sAdd('set', 'item3');
#获取set集合所有值，当值很多的时候，这个方法会很慢
$set = $redis->sMembers('set');
print_r($set);
#检查该值是否在集合当中
$set_key = $redis->sIsMember('set', 'item4');
if ($set_key) {
    echo 'yes!';
} else {
    echo 'no!';
}
#删除值
$redis->sRem('set', 'item1');
$set = $redis->sMembers('set');
print_r($set);

#输出
Array
(
    [0] => item1
    [1] => item3
    [2] => item2
)
no!
Array
(
    [0] => item3
    [1] => item2
)
```

### HASH
哈希表，php的数组怎么玩，他就怎么玩。

```php
#推值
$redis->hSet('hashmap', 'key1', 'value1');
$redis->hSet('hashmap', 'key2', 'value2');
$redis->hSet('hashmap', 'key3', 'value3');
#获取所有值
$hash = $redis->hGetAll('hashmap');
print_r($hash);
#获取单个值
echo $redis->hGet('hashmap', 'key1');
#删除值
$redis->hDel('hashmap', 'key2');
$hash = $redis->hGetAll('hashmap');
print_r($hash);

#输出
Array
(
    [key1] => value1
    [key2] => value2
    [key3] => value3
)
value1
Array
(
    [key1] => value1
    [key3] => value3
)
```

### ZSET
有序集合。也是根据键值对存储，键被称为成员（member），值被称为分值（score），并且必须是浮点型。可以通过值访问，又能通过键访问的一个数据结构。

```php
#推值
$redis->zAdd('zset', 780, 'member1');
$redis->zAdd('zset', 781, 'member2');
$redis->zAdd('zset', 690, 'member3');
#获取所有键值，并且排序好
$zset = $redis->zRange('zset', 0, -1, true);
print_r($zset);
#获取所有键值
$zset = $redis->zRange('zset', 0, -1);
print_r($zset);
#获取范围内的值
print_r($redis->zRangeByScore('zset', 0, 780));
#删除值
$redis->zRem('zset', 'member1');
$zset = $redis->zRange('zset', 0, -1, true);
print_r($zset);

#输出
Array
(
    [member3] => 690
    [member1] => 780
    [member2] => 781
)
Array
(
    [0] => member3
    [1] => member1
    [2] => member2
)
Array
(
    [0] => member3
    [1] => member1
)
Array
(
    [member3] => 690
    [member2] => 781
)
```



