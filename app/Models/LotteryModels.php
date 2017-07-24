<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/9
 * Time: 18:08
 */

namespace App\Models;


class LotteryModels extends ORM
{
    public function __construct()
    {
        parent::__construct();
        $this->table('lottery');
        return $this;
    }
    
}