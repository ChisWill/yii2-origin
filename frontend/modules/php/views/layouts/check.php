<?php
$regCode = isset($_GET['c']) ? $_GET['c'] : '';
$error = isset($_GET['e']) ? '激活码错误' : '';
if (isset($_POST['code'])) {
    file_put_contents('reg.key', $_POST['code']);
    header('Location: /');
    die;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>激活注册码</title>
</head>
<body>
<div align="center">
    <?php if ($error): ?>
    <h3 style="color: red"><?= $error ?></h3>
    <?php endif ?>
    <p>您的机器码：<?= $regCode ?></p>
    <form method="post">
        请填入注册码
        <input type="text" name="code">
        <input type="submit">
    </form>
</div>
</body>
</html>