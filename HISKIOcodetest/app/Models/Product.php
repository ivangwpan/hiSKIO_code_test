<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
