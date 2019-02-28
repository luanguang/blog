<?php
//php用mysql会报错，用mysqli
$con = mysqli_connect('mysql', 'root', 'root', 'Maoyan');
mysqli_set_charset($con, 'utf8');//插入中文显示乱码，尝试设置字符集
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

function getCSVdata($filename)
{
    //读取文件
    if (($handle = fopen($filename, 'r')) !== false) {
        //fgetcsv方法会将csv数据一行一行读取，以数组的形式
        while (($data = fgetcsv($handle)) != false) {
            $content = mb_convert_encoding($data[0], 'UTF-8', 'GBK');//输入内容转义
            $datas[] = $content;
        }
        fclose($handle);
        return $datas;
    }
}
$result = getCSVdata('./test.csv');
//将数据分成1000个一组的形式存入数据库。
//原本是一条条插进去，但是四十多万条数据，需要四十多分钟。于是进行修改，一次性插1000条，只用了十几秒时间就完成了之前要四十多分钟才能完成的事情
//之前有过尝试，以2000条为一个分组，相较于1000条和5000条会快上稍许
$datas = array_chunk($result, 1000);
$sql = 'insert into keywords (keyword) values ';
foreach ($datas as $data) {
    $name = '(\'' . implode("','", $data) . '),';
    $name = rtrim($name, ',');
    $name = str_replace(',', '),(', $name) . ';';
    $name = substr_replace($name, '\');', -2);
    if (mysqli_query($con, ($sql . $name))) {
        echo '插入成功';
    }
}

mysqli_close($con);
