<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:53
 */

namespace Controllers\h5\game;
use Services\LotteryService;

class lottery
{
    public function home()
    {
        $name = !empty($_GET['name']) ? $_GET['name'] : "man";

        $prizes = (new LotteryService)->getPrizes();
        //var_dump($prizes);exit;
        $prize = current($prizes);
        return "你好{$name}, 你获得了{$prize->title}!";
    }
}