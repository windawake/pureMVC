<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:12
 */

include __DIR__."/autoload.php";
define('ROOT_PATH',__DIR__);

$arrUri = parse_url($_SERVER['REQUEST_URI']);
$path = str_replace("/","\\",$arrUri['path']);
$pos = strrpos($path,"\\");
$controllerClass = "Controllers".substr($path,0,$pos);
$actionClass = substr($path,$pos+1);

$instance = new $controllerClass();
$method = new ReflectionMethod($instance, $actionClass);
echo $method->invoke($instance);