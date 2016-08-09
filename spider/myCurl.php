<?php

namespace Spider;

class MyCurl
{
	// 执行一次 curl 请求
	public static function request($url)
	{
		// 创建一个新cURL资源
		$ch = curl_init($url);

		// 设置cURL传输选项
		// 设置报头信息是否包含在数据流输出中
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		// 在HTTP请求中包含一个"User-Agent: "头的字符串
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');
		// curl_exec() 将以字符串形式返回获取的信息，而不是在脚本执行时直接输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		// 根据服务器返回 HTTP 头中的 "Location: " 重定向(这是递归的，"Location: " 发送几次就重定向几次)
		// http://www.le.com/player/x1380568.swf
		// 301重定向=>
		// http://img1.c0.letv.com/ptv/player/swfPlayer.swf?id=1380568
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		// 抓取URL并把返回的数据传递给$result
		$result = curl_exec($ch);
		// 关闭cURL资源，并且释放系统资源
		curl_close($ch);
		return $result;
	}
}
