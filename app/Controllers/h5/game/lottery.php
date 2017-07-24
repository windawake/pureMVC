<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/6
 * Time: 10:53
 */

namespace App\Controllers\h5\game;
use App\Controllers\Controller;
use App\Models\Product;
use App\Models\LotteryModels;
use App\Services\LotteryService;

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
    
    public function model()
    {
        $lottery = (new LotteryModels())->where(array('id >' => 8))->find();
        var_dump($lottery->title);exit;
    }

    public function insert()
    {
        $data = array(
            'sku'       => 1004,
            'title'     => 'css从入门到精通',
        );
        $res = (new Product())->add($data);
    }

    public function update()
    {
        /*$data = array(
            'title'     => 'android',
        );
        $where = array('sku'=>1001);
        $res = (new Product())->where($where)->update($data);*/

        $link2=mysql_connect('127.0.0.1','root','');
        $select_db = mysql_select_db('puremvc', $link2);
        $sql = "update product set titl='android' where id=1";
        $res = mysql_query($sql,$link2);
        $affected = mysql_affected_rows($link2);
        var_dump($affected);
    }

    public function redis()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        $sTime = microtime(true);
        $pipe = $redis->multi(\Redis::PIPELINE);
        for($i=1;$i<100000;$i++){
            $pipe->set($i,"val{$i}");
        }
        $pipe->exec();
        $eTime = microtime(true);
        var_dump($eTime-$sTime);
    }
}