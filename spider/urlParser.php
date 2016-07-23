<?php
/**
* 正则表达式
* PCRE 库
*/
require_once 'myCurl.php';

class urlParser
{

	protected static $configDir = 'config.php';

	static public function parse()
	{
		// include 语句包含并运行指定文件
		$urls = include self::$configDir;

		foreach ($urls as $url) {
			preg_match("/(youku\.com|le\.com|v\.qq\.com)/", $url, $matches);
			if (!$matches) continue;

			switch ($matches[1]) {
				case 'youku.com':
					$data = FALSE;
					break;
				case 'le.com':
					echo "start to download ".$url." ...\n";
					$data = self::parseLetv($url);
					break;
				case 'v.qq.com':
					$data = FALSE;
					break;
				default:
					continue;
			}

			// var_dump($data);
			fileSave::video($data);
		}
	}

	/**
	* 顶点小说网
	* 传入小说目录网址 http://www.23wx.com/html/21/21779/
	* 正则得到每张单独网址 http://www.23wx.com/html/21/21779/14649123.html
	*/
	static public function parseWx($url)
	{
		$html = myCurl::request($url);

		// __DIR__ 返回本文件所在的目录(如果用在被包括文件中，则返回被包括的文件所在的目录)
		// if (file_put_contents(__DIR__.'/../result/test.html', $html)) {
		// 	echo "success\n";
		// } else {
		// 	echo "fail\n";
		// }

		// <a href="14649123.html">第一章 青牛开口</a>
		// 正则模式必须由分隔符闭合包裹，可用任意非字母数字、非反斜线、非空白字符作为分隔符
		// 此处分隔符为 #
		// 圆括号 () 负责将一个完整模式中的一部分标记为子模式
		// 转义序列	\d 任意十进制数字	\s 任意空白字符(包括换行符)
		// 元字符	* 量词,0次或多次匹配	+ 量词,1次或多次匹配
		// 在结束分隔符后加 u 来支持 Unicode(这样 \w 才能匹配中文汉字)
		$pattern = '#vplay\/(\d+)#';
		// preg_match_all函数返回的 matches 数组中, $matches[0][0]将包含首次完整模式所匹配到的文本, $matches[0][1] 将包含首次第一个子模式匹配到的文本，以此类推
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		var_dump($matches);

		$data = [];
		foreach ($matches as $key => $match) {
			$data[$key]['swf'] = "http://www.le.com/player/x".$match[1].".swf";
			$data[$key]['title'] = $match[1];
		}

		return $data;
	}
}