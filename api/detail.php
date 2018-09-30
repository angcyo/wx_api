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

function detail($url)
{
    if (empty($url)) {
        return 'url参数不正确';
    }

    $dom = file_get_html($url);
    resetTag($dom);

    $detail = array();
    $detail['time'] = $dom->find('.nymin')[0]->find('.mig')[0]->find('span')[0]->text();
    $detail['title'] = $dom->find('.nymin')[0]->find('.mig')[0]->find('h5')[0]->text();
    $detail['title'] = str_replace($detail['time'], '', $detail['title']);
    $detail['content'] = $dom->find('.tywb')[0]->xmltext();

    return json_encode($detail, JSON_UNESCAPED_UNICODE);
}