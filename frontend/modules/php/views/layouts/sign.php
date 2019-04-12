<?php
function encode($string)
{
    $exec = function ($data) {
        $key = '82c616cc233a2b57881dac851e437bda86fd455eb8bev97bde76ea1f5324zfgh849ewr12sdf48nhgfbv3qw8fd12cvbxci23213nv3l50pa6exgv9e343t3i98l8w2wz2cv6nm7e10vb2d15sqn';
        $dataLen = strlen($data);
        $keyLen = strlen($key);
        $mod = $dataLen % $keyLen;
        if ($mod > 0) {
            $preLen = floor($dataLen / $keyLen) * $keyLen;
            return (substr($data, 0, $preLen) ^ str_repeat($key, $preLen)) . (substr($data, $preLen) ^ substr($key, 0, $mod));
        } else {
            return $data ^ str_repeat($key, $dataLen / $keyLen);
        }
    };
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($exec($string)));
}

if (isset($_POST['code'])) {
    $code = encode($_POST['code']);
} else {
    $code = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>生成注册码</title>
</head>
<body>
<div align="center">
<?php if ($code): ?>
    <h3 style="color: red">激活码：<?= $code ?></h3>
    <p><a href="javascript:history.go(-1);">点击返回</a></p>
<?php else: ?>
    <form method="post">
        请填入机器码
        <input type="text" name="code">
        <input type="submit">
    </form>
<?php endif ?>
</div>
</body>
</html>