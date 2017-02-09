<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:12
 */

function app_autoload($className)
{
    $file = __DIR__."\\app\\".$className.".php";
    require_once $file;
}

spl_autoload_register("app_autoload");


