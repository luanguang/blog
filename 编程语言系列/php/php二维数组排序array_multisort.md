`array_multisort`这个函数直接去看网上的解释会一脸懵逼，怎么所有字都认识，但是何在一起就看不懂了，更不用提怎么正确的使用了。

但是根据官网给的用例进行使用，就越发觉得，这就是我想要的功能，对二维数组进行排序不要太简单了。

不给排序方式的时候，默认是升序，官方的例子：
```php
$ar1 = array(10, 100, 100, 0);
$ar2 = array(1, 3, 2, 4);

array_multisort($ar1, $ar2);
var_dump($ar1);
var_dump($ar2);

array(4) {
  [0]=> int(0)
  [1]=> int(10)
  [2]=> int(100)
  [3]=> int(100)
}
array(4) {
  [0]=> int(4)
  [1]=> int(1)
  [2]=> int(2)
  [3]=> int(3)
}
```
不是说好升序的吗？怎么第二个完全不是这么回事啊？不急，继续看例子。
```php
$ar = array(
       array("10", 11, 100, 100, "a"),
       array(   1,  2, "2",   3,   1)
      );
array_multisort(
    $ar[0],
    SORT_ASC,
    SORT_STRING,
    $ar[1],
    SORT_NUMERIC,
    SORT_DESC
);
var_dump($ar);

array(2) {
  [0] =>
  array(5) {
    [0] =>
    string(2) "10"
    [1] =>
    int(100)
    [2] =>
    int(100)
    [3] =>
    int(11)
    [4] =>
    string(1) "a"
  }
  [1] =>
  array(5) {
    [0] =>
    int(1)
    [1] =>
    int(3)
    [2] =>
    string(1) "2"
    [3] =>
    int(2)
    [4] =>
    int(1)
  }
}
```
这个例子是让第一个数组根据字符串升序，第二个数组根据数字降序。但是还是有点奇怪，跟想象的依旧不一样，跟第一个例子一样，第二个数组没有按照这个规则来。

其实是按照规则排序了。把数组改变一种方式展示。

id | value|
---|---|
10 | 1 |
100| 3 |
100| 2 |
0  | 4 |

根据第一次的排序

id | value|
---|---|
0  | 4 |
10 | 1 |
100| 2 |
100| 3 |

就像数据库一样，先排序第一个选中的字段，假如第一个字段有相同的，那么就根据第二个字段进行排序。把这个规则套在第二个例子上，就能说清楚为什么结果会跟想象的不一样了。

这个函数就是实现了跟数据库一样的功能，太猛了。

第三个例子
```php
$data[] = array('volume' => 67, 'edition' => 2);
$data[] = array('volume' => 86, 'edition' => 1);
$data[] = array('volume' => 85, 'edition' => 6);
$data[] = array('volume' => 98, 'edition' => 2);
$data[] = array('volume' => 86, 'edition' => 6);
$data[] = array('volume' => 67, 'edition' => 7);

$volume = array();
$edition = array();
foreach ($data as $key => $row) {
    $volume[$key]  = $row['volume'];
    $edition[$key] = $row['edition'];
}

array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);
```

volume | edition|
---|---|
98 |  2|
86 |  1|
86 |  6|
85 |  6|
67 |  2|
67 |  7|

没错就是根据`volume`这个字段进行排序完成了。

平时用`array_column`和`array_multisort`搭配使用，效果更佳。
