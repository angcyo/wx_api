<?php
/**
 * Created by IntelliJ IDEA.
 * User: angcyo
 * Date: 2018/9/29 0029
 * Time: 10:13
 */
header("Content-type: text/html; charset=utf-8");
header('Content-language: zh');

define("APP", true);
define("BASE_URL", "https://www.gongzhujia.com.cn");

require_once('./api/get_news.php');
require_once('./api/detail.php');
require_once('./api/home.php');
require_once('./api/product.php');

$query_args = $_SERVER['QUERY_STRING'];

logToFile('request.log',
    getValue('HTTP_USER_AGENT') . "\n" .
    getValue('REMOTE_ADDR') . ':' . getValue('REMOTE_HOST') . ':' . getValue('REMOTE_PORT') . ':' . getValue('REMOTE_USER') . "\n" .
    $query_args
);

try {
    $rws_post = $GLOBALS['HTTP_RAW_POST_DATA'];
    logToFile('request.log', 'post:' . $rws_post . '->' . file_get_contents("php://input"));
} catch (Exception $e) {
}

if (empty($query_args)) {
    echo 'no query';
} else {
    //var_dump($_REQUEST);
    $do = $_REQUEST['do'];
    switch ($do) {
        case 'get_news':
            //获取新闻列表
            $page = (int)$_REQUEST['page'];
            $newsType = (int)$_REQUEST['newsType'];
            //获取第几页
            echo getNewsList($page, $newsType);
            break;
        case 'detail':
            echo detail($_REQUEST['url']);
            break;
        case 'home':
            echo home();
            break;
        case 'product':
            echo product($_REQUEST['url']);
            break;
        default:
            echo '不支持的命令:' . $do;
            break;
    }
}

//chmod -R 777 .
function logToFile($fileName, $log)
{
    //date_default_timezone_set("Asia/Shanghai");

    if (strcasecmp(date_default_timezone_get(), 'PRC') == 0 ||
        strcasecmp(date_default_timezone_get(), 'UTC') == 0) {
        $time = time();
    } else {
        $time = time() - 8 * 60 * 60;
    }
    file_put_contents($fileName, date_default_timezone_get() . ' ' . date("Y-m-d H:i:s", $time) . "\n" . $log . "\n\n", FILE_APPEND);
}

function getValue($key)
{
    $value = '';
    if (isset($_SERVER[$key])) {
        $value = $_SERVER[$key];
    }
    return $value;
}