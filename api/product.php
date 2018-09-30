<?php
/**
 * Created by IntelliJ IDEA.
 * User: angcyo
 * Date: 2018/9/29 0029
 * Time: 10:13
 */

header("Content-type: text/html; charset=utf-8");
header('Content-language: zh');

if (!defined('APP')) {
    die('非法访问.');
}

require_once './lib/simple_html_dom.php';

function product($url)
{
    if (empty($url)) {
        return 'url参数不正确';
    }
    $dom = file_get_html($url);
    resetTag($dom);

    $result = array();
    $result['title'] = $dom->find('title')[0]->text();

    if (strpos($url, 'about.html')) {
        $spans = $dom->find('.origin-img');
        $imgs = array();
        foreach ($spans as $item) {
            $imgs[] = $item->find('img')[0];
        }

    } else {
        $imgs = $dom->find('.nymin')[0]->find('img');
    }

    foreach ($imgs as $item) {
        $result['imgs'] [] = $item->attr['src'];
    }

    return json_encode($result, JSON_UNESCAPED_UNICODE);
}