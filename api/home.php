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

function home()
{
    $url = 'https://www.gongzhujia.com.cn/Index/index.html';
    $dom = file_get_html($url);
    resetTag($dom);

    $home = array();

    //导航图
    $home['nav_pagers'] = array();
    $nav_pagers = $dom->find('#camera_wrap_1')[0]->children();
    foreach ($nav_pagers as $item) {
        $home['nav_pagers'][] = BASE_URL . $item->attr['data-src'];
    }

    //内容
    $home['contents'] = array();
    $contents = $dom->find('.intitle');
    foreach ($contents as $item) {
        $a = array();
        $a['tip'] = $item->find('h2')[0]->text();
        $a['tip_en'] = strtoupper($item->find('span')[0]->text());
        $a['des'] = $item->find('h5')[0]->text();


        $parent = $item->parent();

        $imgs = $parent->find('img');
        foreach ($imgs as $item) {
            $a['img'][] = $item->attr['src'];
        }

        $links = $parent->find('a');
        foreach ($links as $item) {
            $a['link'][] = $item->attr['href'];
        }

        $home['contents'][] = $a;
    }

//    $home['time'] = $dom->find('.nymin')[0]->find('.mig')[0]->find('span')[0]->text();
//    $home['title'] = $dom->find('.nymin')[0]->find('.mig')[0]->find('h5')[0]->text();
//    $home['title'] = str_replace($home['time'], '', $home['title']);
//    $home['content'] = $dom->find('.tywb')[0]->xmltext();

    return json_encode($home, JSON_UNESCAPED_UNICODE);
}