<?php
/**
* 保存抓取的资源
* Filesystem 库
*/
require_once 'urlParser.php';

class fileSave
{
	
	public static function novel($data)
	{
		foreach ($data as $each) {
			$content = myCurl::request($each['content']);

			if (file_put_contents(__DIR__.'/../result/'.$each['title'].'.swf', $content)) {
				echo "succeed to save ".$each['title'].".swf!\n";
			} else {
				echo "fail to save ".$each['title'].".swf!\n";
			}
		}
	}
}