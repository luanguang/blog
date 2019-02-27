<?php
$con = mysqli_connect('mysql', 'root', 'root', 'Maoyan');
mysqli_set_charset($con, 'utf8');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

function getCSVdata($filename)
{
    $row = 1;
    if (($handle = fopen($filename, 'r')) !== false) {
        while (($data = fgetcsv($handle)) != false) {
            $content = mb_convert_encoding($data[0], 'UTF-8', 'GBK');
            $datas[] = $content;
            if ($row >= 100) {
                break;
            }
            $row++;
        }

        fclose($handle);
        return $datas;
    }
}
$result = getCSVdata('./内衣长尾词_1550133544.csv');
$datas = array_chunk($result, 1000);
$sql = 'insert into keywords (keyword) values ';
foreach ($datas as $data) {
    $name = '(\'' . implode("','", $data) . '),';
    $name = rtrim($name, ',');
    $name = str_replace(',', '),(', $name) . ';';
    $name = substr_replace($name, '\');', -2);
    // print($name);
    if (mysqli_query($con, ($sql . $name))) {
        echo '插入成功';
    }
}
// mysqli_query($con, "INSERT INTO xiaohongshu(url, author, time, content) values ('test', 'test', 'test', '<div data-v-f8e7a182=\"\" class=\"content\"><h1 data-v-f8e7a182=\"\" class=\"as-p\">70%的新 娘都买错内衣了，剩下的30%看这里...</h1><p data-v-f8e7a182=\"\">不同款式的婚纱，搭配什么内衣最合适呢？你的婚纱是什么款式 ？照着买就可以啦！</p><p data-v-f8e7a182=\"\">1-深V婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：V形 / U形 / 超低心设计</p><p data-v-f8e7a182=\"\">如果想让曲线更完美，可以选择带聚拢效果的，这样原本气场就很强的新娘，女王范更加up up。</p><p data-v-f8e7a182=\"\">根据婚纱不同的V形深度，选择对应深度的内衣的哦。</p><p data-v-f8e7a182=\"\">2-露背婚纱</p><p data-v-f8e7a182=\"\">搭配内衣： Nubra（隐形胸贴） / 后背交叉式内衣</p><p data-v-f8e7a182=\"\">选择Nubra会让背部曲线特别完整哦，但如果感觉 这样没有安全感，后背交叉式内衣也是不错的。</p><p data-v-f8e7a182=\"\">3-鱼尾婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：无痕的塑形内衣</p><p data-v-f8e7a182=\"\">鱼尾婚纱哪有想象中的那么挑身材，给自己选择一件无痕的塑形内衣，你的身材会好得超乎你想象哦！</p><p data-v-f8e7a182=\"\">4-抹胸婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：Nubra（隐形胸贴）</p><p data-v-f8e7a182=\"\">抹胸婚纱是婚纱里最受欢迎的款式。因为整个受力和亮点都在胸部，所以搭配隐形胸贴是最好的。光滑的材质能够完美地贴合胸 部，不留痕迹地塑造完美的曲线。</p><p data-v-f8e7a182=\"\">5-贴身真丝婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：光滑无缝内衣</p><p data-v-f8e7a182=\"\">虽然真丝和蕾丝是绝佳的组合，但真丝婚纱千万不能配蕾丝哦。</p><p data-v-f8e7a182=\"\">6-蓬蓬婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：收腰内衣</p><p data-v-f8e7a182=\"\">蓬蓬裙非常适合外形甜美的新娘，但因为裙摆蓬松，新娘的曲线就会被一定程度的遮掩。此时，如果能完美地露出小蛮腰，可以说是整个造型的点睛之笔哦。</p><p data-v-f8e7a182=\"\">选择收腰内衣就显得十分关键。</p><p data-v-f8e7a182=\"\">7-挂脖式露背婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：挂脖式内衣</p><p data-v-f8e7a182=\"\">挂脖婚纱的好处就是，你正好有隐藏内衣的地方。所以选择挂脖式内衣是再好不过的了。不仅曲线完美，还很有安全感。</p><p data-v-f8e7a182=\"\">8-单肩婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：单肩内衣 / 可拆卸肩带内衣</p><p data-v-f8e7a182=\"\">至于具体选择哪一款，要根据后背露出的多少决定哦！</p><p data-v-f8e7a182=\"\">9-轻透视婚纱</p><p data-v-f8e7a182=\"\">搭配内衣：乳贴</p><p data-v-f8e7a182=\"\">近几年国外很多大牌都会出一两款轻透视的婚纱。和时装一样，婚纱采用透视 元素，若隐若现十分性感，就凭一副好身材，撑起了所有的美艳。</p><p data-v-f8e7a182=\"\">因为这类婚纱和时装的兴起，乳贴这种神器也应运而生啊。如果你的婚纱款式有这些特殊要求，试着用用这款小神器哦。</p><p data-v-f8e7a182=\"\">最后，再再啰嗦几句：</p><p data-v-f8e7a182=\"\">1、试纱时，穿着合身的内衣去。如果选择了婚纱款式，可进一步和礼服师沟通内衣搭配；</p><p data-v-f8e7a182=\"\">2、婚礼时不要硬挤，一不小心挤出副乳就会适得其反，自然舒适最重要；</p><p data-v-f8e7a182=\"\">3、不管婚礼当 天是穿塑身内衣还是nubra，婚礼前都要试穿一天，如果有不合适的调整还来得及。</p></div>')");

mysqli_close($con);