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

/**
 * 1 https://www.gongzhujia.com.cn/News/news/p/2.html 品牌资讯
 * 2 https://www.gongzhujia.com.cn/News/news/qid/2/p/2.html 品牌新闻
 * 3 https://www.gongzhujia.com.cn/News/news/qid/3/p/2.html 行业新闻
 * 4 https://www.gongzhujia.com.cn/News/news/qid/5/p/3.html 公司新闻
 */
function getNewsList($page, $type)
{
    if (is_int($page)) {
        //$type_url =
        switch ($type) {
            case 2:
                $type_url = '/News/news/qid/2/p/';
                break;
            case 3:
                $type_url = '/News/news/qid/3/p/';
                break;
            case 4:
                $type_url = '/News/news/qid/5/p/';
                break;
            default:
                $type_url = '/News/news/p/';
                break;
        }

        $dom = file_get_html(BASE_URL . $type_url . $page . ".html");

        if ($dom) {
            try {
                resetTag($dom);
                $nymin = $dom->find('.nymin > .am-container');
                $items = $nymin[0]->find('a.am-panel');

                $news = array();
                $new = array();
                foreach ($items as $item) {
                    //每条新闻内容, 包含在一个a标签内
                    //  echo $item->text() . '<br/>';
                    //echo $item->attr['href'] . '<br/>';
                    //$news[] = $item->attr['href'];

                    $new['link'] = $item->attr['href'];
                    $new['img'] = $item->find('img')[0]->attr['src'];

                    $center = $item->find('.am-panel-center')[0];
                    $new['title'] = $center->find('.am-text-color-base')[0]->text();
                    $new['time'] = $center->find('.am-text-color-third')[0]->text();
                    $new['content'] = $center->find('p')[0]->text();

                    $news[] = $new;
                }
                //var_dump($news);
//        echo json_encode($new);
                return json_encode($news, JSON_UNESCAPED_UNICODE);
                //echo $dom;
                //var_dump($nymin);
            } catch (Exception $e) {
                return "数据异常.";
            }
            $dom->clear();
        }
    }
    return "无数据";
}

function resetTag($dom)
{
    if ($dom) {
        $imgs = $dom->find('img');
        if ($imgs) {
            foreach ($imgs as $img) {
                $img->attr['src'] = BASE_URL . $img->attr['src'];
            }
        }

        $as = $dom->find('a');
        if ($as) {
            foreach ($as as $a) {
                $a->attr['href'] = BASE_URL . $a->attr['href'];
            }
        }
    }
}

//var_dump($dom);

//echo $dom;

//$content = file_get_contents("http://www.gongzhujia.com.cn/News/news/p/1.html");
//echo $content;