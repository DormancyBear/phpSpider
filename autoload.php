<?php
/* include_path => php.ini 配置选项
* 值为一堆目录的列表(类似于PATH环境变量, 在Unix中以 : 分隔各个目录, 而在 Windows 中以 ; 进行分隔)
* require, include, fopen(), file(), readfile(), file_get_contents() 会依次检查各个目录，在其中寻找所需的文件
*/
// dirname() => 返回此文件路径中的目录部分
// set_include_path() => 为当前脚本设置运行时 include_path 的配置选项
// get_include_path() => 获取当前 include_path 配置选项的值
// PATH_SEPARATOR => 预定义常量, 在Unix中为 : ,在Windows中为 ;
$feature_include_path = __DIR__ . '/feature' . PATH_SEPARATOR . __DIR__ . '/spider';
set_include_path(get_include_path() . PATH_SEPARATOR . $feature_include_path);

// 传进来的 $class 格式是 Namespace\ClassName, 如 Spider\Crawler, 并不只是单纯一个类名而已
function spider_autoloader($class)
{
    $pos = strrpos($class, '\\');
    if ($pos === false) {
        $ClassName = $class;
    } else {
        $ClassName = substr($class, $pos+1);
    }

    include_once $ClassName . '.php';
}

// bool spl_autoload_register (callable $autoload_function)
// 将给定函数注册到 SPL __autoload 函数队列中(基本功能同 __autoload)
// __autoload() => 此函数将会在试图使用未被定义的类时自动调用
// spl_autoload_register() 实际上创建了一个 autoload 函数的队列, 会在触发时按定义的顺序逐个执行
// 注意回调类型 callable 其实是以 string 类型进行名称的传递
spl_autoload_register('spider_autoloader');
