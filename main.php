<?php

use Feature\CrawlWx;

require_once 'autoload.php';

$urls = include_once "config.php";

foreach ($urls as $url) {
    preg_match("/(xxbiquge\.com|23wx\.com|bxwx8\.org)/", $url, $matches);
    if (!$matches) {
        continue;
    }

    switch ($matches[1]) {
        case 'xxbiquge.com':
            $data = false;
            break;
        case '23wx.com':
            echo "start to download ".$url." ...\n";
            $mycrawler = new CrawlWx($url);
            $mycrawler->run();
            break;
        case 'bxwx8.org':
            $data = false;
            break;
        default:
            continue;
    }
}
