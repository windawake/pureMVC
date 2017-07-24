<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/9
 * Time: 18:37
 */

namespace App\Services;
use App\Models\Product;

class LotteryService
{
    public function getPrizes()
    {
        $product = new Product;
        $arrProduct = $product->select();
        return $arrProduct;
    }
}