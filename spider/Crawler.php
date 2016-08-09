<?php

namespace Spider;

require_once 'myCurl.php';

class Crawler
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function run()
    {
        // string iconv ( string $in_charset , string $out_charset , string $str )
        // 将字符串 str 从 in_charset 转换编码到 out_charset

        // 取到的网页内容编码为GBK,很多PHP函数在这种编码下无法正常工作
        $html = iconv("GBK", "UTF-8", MyCurl::request($this->url));
        $this->parsePage($this->url, $html);
    }
}
