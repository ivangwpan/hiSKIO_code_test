<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 題目二：
請你設計出購物車的函式庫，至少包含一個以上的物件，此函式庫必須包含以下功能：

欄位資訊：

商品：
品名、單價、數量、折扣金額、總計金額
優惠折扣：
優惠名稱、折扣金額
購物車功能：

商品的 CRUD：
新增商品
移除商品
更新商品數量
優惠折扣
單一品項增加優惠折扣
單一品項移除優惠折扣
取得購物車內商品清單(顯示品名、數量、單價、折扣金額 & 優惠名稱、結帳金額)
取得購物車總共金額
注意：

每一個商品都只能新增一個優惠折扣，一但有重複加入的狀況，需提示已使用優惠折扣，並且無法加入優惠折扣。
優惠折扣至少需做到扣除金額與打折的功能。舉例
扣除金額: 折價 10 元，100 - 10 = 90 元
打 85 折：100 * 0.85 = 85 元
 */
class Product extends Model
{
    public $name;
    public $price;
    public $quantity;
    public $discountAmount;
    public $totalPrice;
    public $discountPercentage;

    public function __construct($name, $price, $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->discountAmount = 0;
        $this->totalPrice = $this->price * $this->quantity;
        $this->discountPercentage = 0;
    }

    public function applyDiscountAmount($discountAmount)
    {
        if ($this->discountPercentage > 0) {
            echo "{$this->name} 已有使用折扣百分比，無法同時折扣價格。<br>";
        } else {
            $this->discountAmount = $discountAmount;
            $this->totalPrice = max(0, $this->price - $discountAmount) * $this->quantity;
        }
    }

    public function applyDiscountPercentage($discountPercentage)
    {
        if ($this->discountAmount > 0) {
            echo "{$this->name} 已有使用折扣價格，無法同時折扣百分比。<br>";
        } else {
            $this->discountPercentage = $discountPercentage;
            $discountAmount = $this->price * $discountPercentage;
            $this->totalPrice = max(0, $this->price - $discountAmount) * $this->quantity;
        }
    }

    public function removeDiscount()
    {
        $this->discountAmount = 0;
        $this->discountPercentage = 0;
        $this->totalPrice = $this->price * $this->quantity;
    }
}
