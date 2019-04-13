快速排序用到了递归。递归用到了一种叫做栈的数据结构。栈的执行顺序是先进后出。也就是最开始的方法是最后一个执行。
```php
function name($x)
{
    if ($x == 1) {
        return 1;
    }
    return $x * name($x - 1);
}

echo name(5);
```
这个方法执行的顺序是
```
1*2
2*3
6*4
24*5
```

快速排序的思路是分而治之。将一个大问题慢慢分解成小问题。比如有20个打乱的数，我从中随机找一个数，然后将其他数进行比较，大于这个数和小于这个数的数字分成两批。而在这两批数里继续找一个数，然后再分成两批，直到两批数只有一个或者为空的时候，返回。
python 快速排序
```python
def quicksort(x):
    if len(x) <= 1:
        return x
    pivot = x[0]
    less = []
    high = []
    for i in x[1:]:
        if i <= pivot:
            less.append(i)
        else:
            high.append(i)
    return (quicksort(less) + [pivot] + quicksort(high))
```
php 快速排序
```php
<?php
function quicksort($arr)
{
    if (count($arr) < 2) {
        return $arr;
    } else {
        $pivot = $arr[0];
        $less = [];
        $newArr = array_slice($arr, 1);
        foreach ($newArr as $value) {
            if ($value <= $pivot) {
                $less[] = $value;
            }
        }
        $greater = [];
        foreach ($newArr as $val) {
            if ($val > $pivot) {
                $greater[] = $val;
            }
        }
        return array_merge(quicksort($less), [$pivot], quicksort($greater));
    }
}
```
用php写算法，有点尴尬……各种方法
