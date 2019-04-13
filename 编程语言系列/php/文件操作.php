<?php
ini_set('display_errors', true);
error_reporting(-1);
/*
 *is_dir — 判断给定文件名是否是一个目录
 *
 *is_file — 判断给定文件名是否为一个文件
 *
 *is_executable — 判断给定文件名是否可执行
 *
 *is_link — 判断给定文件名是否为一个符号连接
 *
 *is_readable — 判断给定文件名是否可读
 *
 *is_uploaded_file — 判断文件是否是通过 HTTP POST 上传的
 *
 *is_writable — 判断给定的文件名是否可写
 *
 *is_writeable — is_writable 的别名
 *
 *file_exists — 检查文件或目录是否存在

 *fileatime — 取得文件的上次访问时间
 *
 *filectime — 取得文件的 inode 修改时间
 *
 *filegroup — 取得文件的组
 *
 *fileinode — 取得文件的 inode
 *
 *filemtime — 取得文件修改时间
 *
 *fileowner — 取得文件的所有者
 *
 *fileperms — 取得文件的权限
 *
 *filesize — 取得文件大小
 *
 *filetype — 取得文件类型
 */

function getFileInfo($filename)
{
    if (!file_exists($filename)) {
        echo '文件' . ($filename) . '不存在';
        return;
    }

    if (is_file($filename)) {
        echo $filename . '是一个文件<br>';
    }
    if (is_dir($filename)) {
        echo $filename . '是一个目录<br>';
    }
    if (is_executable($filename)) {
        echo $filename . '是一个可执行文件<br>';
    } else {
        echo $filename . '不是可执行文件<br>';
    }
    if (is_readable($filename)) {
        echo $filename . '是可读文件<br>';
    } else {
        echo $filename . '不是可读文件<br>';
    }
    if (is_writeable($filename)) {
        echo $filename . '是可修改文件<br>';
    } else {
        echo $filename . '不是可修改文件<br>';
    }

    echo '文件' . $filename . '的大小是' . getFilesize(filesize($filename)) . '<br>';
    echo '文件' . $filename . '的类型是' . filetype($filename) . '<br>';
    echo '文件' . $filename . '的所有者是' . fileowner($filename) . '<br>';
    echo '文件' . $filename . '的权限' . fileperms($filename) . '<br>';
    echo '文件' . $filename . '的inode是' . fileinode($filename) . '<br>';
    echo '文件' . $filename . '的修改时间是' . getTime(filemtime($filename)) . '<br>';
    echo '文件' . $filename . '的最后访问时间是' . getTime(fileatime($filename)) . '<br>';
}

function getTime($time)
{
    return date('Y-m-d H:i:s', $time);
}

function getFilesize($size)
{
    $ft = 'B';
    if ($size >= pow(2, 40)) {
        $size = round($size / pow(2, 40), 2);
        $ft = 'TB';
    } elseif ($size >= pow(2, 30)) {
        $size = round($size / pow(2, 30), 2);
        $ft = 'GB';
    } elseif ($size >= pow(2, 20)) {
        $size = round($size / pow(2, 20), 2);
        $ft = 'MB';
    } elseif ($size >= pow(2, 10)) {
        $size = round($size / pow(2, 10), 2);
        $ft = 'KB';
    }
    return $size . $ft;
}
getFileInfo('design.php');

/**
 * basename -- 返回路径中的文件名部分
 * dirname -- 返回路径中的目录部分
 * pathinfo -- 返回文件路径的信息
 */
$url1 = './aaa/bbb/index.php';
echo basename($url1) . '<br>';
echo dirname($url1) . '<br>';
print_r(pathinfo($url1)) . '<br>';

/**
 * touch -- 创建一个文件
 * unlink -- 删除文件
 * rename -- 重命名一个文件或目录
 * copy -- 拷贝文件
 */

/**
 * 传入文件名，直接得到文件中的文本信息，返回的内容即为文件中的文本。没有这个文件就报错，有就把全部内容返回。
 */
$str = file_get_contents('1.text');
echo $str . '<br>';

/**
 *写入文件，filename是写入文件的文件名，content是写入内容，返回值是成功写入的字符长度。没有文件则创建，有文件则先清空
 */
echo file_put_contents('1.text', 'abcd123') . '<br>';
/**
 *file是直接打开某一个文件，返回的结果是一个数组，每一行是数组的一个元素。也是读取全部内容
 */
$str1 = file('1.text');
var_dump($str1) . '<br>';
echo count($str1) . '<br>';

/**
 * r，以只读模式打开文件
 * r+，除了读，还可以写入。（从文件头开始写，保留原文件中没有被覆盖的内容）
 * w， 以只写的方式打开，如果文件不存在，则创建这个文件,并写放内容，如果文件存在，并原来有内容，则会清除原文件中所有内容，再写入（打开已有的重要文件）
 * w+，除了可以写用fwrite, 还可以读fread（如果文件存在，清空，从头开始写）
 * a，以只写的方式打开，如果文件不存在，则创建这个文件，并写放内容，如果文件存在，并原来有内容，则不清除原有文件内容，再原有文件内容的最后写入新内容，（追加）
 * a+，除了可以写用fwrite, 还可以读fread
 * b，以二进制模式打开文件（图，电影）
 * t，以文本模式打开文件
 *
 *
 * file是文件资源，用fopen函数获取来的，content是写入内容。
 */
$file = fopen('1.text', 'r+');
$result = fwrite($file, 'xx');
if ($result) {
    echo 'success<br>';
} else {
    echo 'failed<br>';
}
fclose($file);
/**
 *读取文件指定部分的长度，file是文件资源，由fopen返回的对象，size是读取字符的长度。打开的文件记得要用fclose关闭
 */
$file1 = fopen('1.text', 'r');
$content = fread($file1, filesize('1.text'));
echo $content . '<br>';
fclose($file1);
/**
 *file是文件资源，每次读取一行。例如我们读取出腾讯首页一共有多少行。
 */
$file2 = fopen('http://www.qq.com', 'r');
$str = '';
$count = 0;
while (!feof($file2)) {
    $str .= fgets($file2);
    $count++;
}
echo $count . '<br>';
// echo $str;
fclose($file2);
/**
 * 与fgets方法很相似，file是文件资源，每次读取个字符。
 */
$file3 = fopen('1.text', 'r');
$str = '';
$count = 0;
while (!feof($file3)) {
    $str .= fgetc($file3);
    $count++;
}
echo $count . '<br>';
fclose($file3);
