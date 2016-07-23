<?php
/**
* 保存抓取的资源
* Filesystem 库
*/
require_once 'urlParser.php';

class fileSave
{
	
	public static function video($data)
	{
		foreach ($data as $each) {
			$resource = myCurl::request($each['swf']);

			if (file_put_contents(__DIR__.'/../result/'.$each['title'].'.swf', $resource)) {
				echo "succeed to save ".$each['title'].".swf!\n";
			} else {
				echo "fail to save ".$each['title'].".swf!\n";
			}
		}
	}
}