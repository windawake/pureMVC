<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:53
 */

namespace h5\game;

class lottery
{
    public function home()
    {
        $name = !empty($_GET['name']) ? $_GET['name'] : "man";
        
        return "hello {$name}, welcome to lottery game!";
    }
}