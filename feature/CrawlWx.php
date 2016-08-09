<?php

namespace Feature;

use Spider\Crawler;
use Spider\MyCurl;

/**
* 顶点小说网
* 传入小说目录网址 http://www.23wx.com/html/21/21779/
* 正则得到每张单独网址 http://www.23wx.com/html/21/21779/14649123.html
*/
class CrawlWx extends Crawler
{

    public function parsePage($url, $html)
    {
        // <a href="http://www.23wx.com/book/21779">大圣传</a></dt>
        // 量词后紧跟 ? 标记 => 将量词转化为懒惰模式(如下面的模式, .+ 后碰到第一个双引号立马就结束匹配)
        $name_pattern = '#<a href=".+">(\w+)<\/a><\/dt>#u';
        preg_match($name_pattern, $html, $name_match);

        $novel_name = iconv("UTF-8", "GBK", __DIR__.'/../result/'.$name_match[1].'.txt');
        // 'a' => add,写入方式打开,将文件指针指向文件末尾(如果文件不存在则尝试创建之)
        $novel_fp = fopen($novel_name, 'a');
        // PHP_EOL 换行符(适应各种系统)
        fwrite($novel_fp, $name_match[1].PHP_EOL);

        // <td class="L"><a href="14649123.html">第一章 青牛开口</a></td>
        // 正则模式必须由分隔符闭合包裹，可用任意非字母数字、非反斜线、非空白字符作为分隔符
        // 此处分隔符为 #
        // 圆括号 () 负责将一个完整模式中的一部分标记为子模式
        // 转义序列 \d 任意十进制数字  \s 任意空白字符(包括换行符)
        // 元字符  * 量词,0次或多次匹配    + 量词,1次或多次匹配
        // 默认情况下,量词都是“贪婪”的(在不导致模式匹配失败的前提下,尽可能多的匹配字符,如下面的模式,(\w+)后碰到第一个双引号并不会立马结束匹配,仍然会试图向后寻找下一个双引号)
        // 在结束分隔符后加修饰符 u 来支持 Unicode(这样 \w 才能匹配中文汉字)
        $pattern = '#<td class="L"><a href="(.+?)">(.+?)<\/a><\/td>#';
        // preg_match_all函数返回的 matches 数组中, $matches[0][0]将包含首次完整模式所匹配到的文本, $matches[0][1] 将包含首次第一个子模式匹配到的文本，以此类推
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $chapter_url = $url.$match[1];
            $content = iconv("GBK", "UTF-8", MyCurl::request($chapter_url));
            // 修饰符 s => 使 . 匹配所有字符(包括换行符)
            $content_pattern = '#<dd id="contents".*?>(.*?)</dd>#s';
            preg_match($content_pattern, $content, $content_match);

            $novel_content = $content_match[1];
            $novel_content = str_replace(array('<br>','<br />'), PHP_EOL, $novel_content);
            $novel_content = str_replace('&nbsp;', ' ', $content_match[1]);
            // strip_tags => 给字符串去除所有的HTML和PHP标记
            $novel_content = strip_tags($novel_content);

            fwrite($novel_fp, iconv("UTF-8", "GBK", $match[2].PHP_EOL));
            fwrite($novel_fp, iconv("UTF-8", "GBK", $novel_content.PHP_EOL));
            echo '已完成'.$match[2].PHP_EOL;
        }

        fclose($novel_fp);
    }
}
