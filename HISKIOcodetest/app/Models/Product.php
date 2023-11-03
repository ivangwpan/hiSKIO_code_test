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
    public $discount;

    public function __construct($name, $price, $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->discountAmount = 0;
        $this->totalPrice = $this->price * $this->quantity;
        $this->discount = null;
    }

    public function applyDiscount($discountAmount)
    {
        if ($this->discount) {
            echo "{$this->name} 已有使用優惠.<br>";
        } else {
            $this->discountAmount = $discountAmount;
            $this->totalPrice = ($this->price - $discountAmount) * $this->quantity;
            $this->discount = "優惠金額 $discountAmount 已使用到 {$this->name}.";
        }
    }

    public function removeDiscount()
    {
        $this->discountAmount = 0;
        $this->totalPrice = $this->price * $this->quantity;
        $this->discount = null;
    }
}
