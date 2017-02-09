<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/9
 * Time: 18:08
 */

namespace Models;


class Product extends ORM
{
    public function __construct()
    {
        parent::__construct();
        //$this->table('product');
        return $this;
    }

    /**
     * 造成破坏的例子
     * @return array
     */
    public function getData()
    {
        return array(
            ['id'=>1,'name'=>'第一件商品'],
            ['id'=>2,'name'=>'第二件商品'],
        );
    }
}