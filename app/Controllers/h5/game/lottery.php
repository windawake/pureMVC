<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:53
 */

namespace Controllers\h5\game;
use Controllers\Controller;
use Services\LotteryService;

class lottery extends Controller
{
    public function home()
    {
        $prizes = (new LotteryService)->getPrizes();
        $data = array(
            'products'=>$prizes,
            'level'=>2,
        );
        return $this->view('lottery',$data);
    }
}