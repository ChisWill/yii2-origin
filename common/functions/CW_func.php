<?php
/**
 * 调试专用，可以传入任意多的变量进行打印查看
 */
function tes()
{
    $isCli = PHP_SAPI === 'cli';
    if (!$isCli && !in_array('Content-type:text/html;charset=utf-8', headers_list())) {
        header('Content-type:text/html;charset=utf-8');
    }
    global $_debugFunc;
    $_debugFunc = $_debugFunc ?: 'print_r';
    foreach (func_get_args() as $msg) {
        if ($isCli) {
            $_debugFunc($msg);
            echo PHP_EOL;
        } else {
            if ($_debugFunc === 'var_dump') {
                $_debugFunc($msg);
            } else {
                echo '<xmp>';
                $_debugFunc($msg);
                echo '</xmp>';
            }
        }
    }
}

/**
 * @see tes()
 */
function test()
{
    global $_debugFunc;
    $_debugFunc = 'print_r';
    call_user_func_array('tes', func_get_args());
    exit;
}

/**
 * @see tes()
 */
function dump()
{
    global $_debugFunc;
    $_debugFunc = 'var_dump';
    call_user_func_array('tes', func_get_args());
    exit;
}

/**
 * 文件方式调试
 * 
 * @param mixed $data 任意变量
 */
function ftes($data)
{
    file_put_contents('file_log.txt', date('Y-m-d H:i:s') . ':' . var_export($data, true) . "\r\n", FILE_APPEND);
}

/**
 * 判断是否是质数
 */
function isPrime($number)
{
    if ($number == 2 || $number == 3) {
        return true;
    }
    if ($number % 6 != 1 && $number % 6 != 5) {
        return false;
    }
    $sqrt = sqrt($number);
    for ($i = 5; $i <= $sqrt; $i += 6) {
        if ($number % $i == 0 || $number % ($i + 2) == 0) {
            return false;
        }
    }
    return true;
}
