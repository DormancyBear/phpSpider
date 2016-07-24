# phpSpider

## Quick Start

### http://www.23wx.com/

**Setup your spider(./spider/config.php)**

```php
return [
	'http://www.23wx.com/html/21/21779/',
];
```

在配置数组中直接加入想要下载小说的目录页面即可。

**Boot up your spider**

```php
php main.php
```

小说资源将自动以`小说名.txt`为名保存到`result`目录下。