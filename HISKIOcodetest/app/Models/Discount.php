<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public $cart = array();

    public function addProduct($product)
    {
        $this->cart[] = $product;
    }

    public function removeProduct($product)
    {
        $index = array_search($product, $this->cart);
        if ($index !== false) {
            array_splice($this->cart, $index, 1);
        }
    }

    public function updateQuantity($product, $quantity)
    {
        $product->quantity = $quantity;
        $product->totalPrice = $product->price * $quantity;
    }

    public function applyDiscountToProduct($product, $discountAmount)
    {
        $product->applyDiscount($discountAmount);
    }

    public function removeDiscountFromProduct($product)
    {
        $product->removeDiscount();
    }

    public function getCartList()
    {
        foreach ($this->cart as $product) {
            echo "產品: {$product->name}, 數量: {$product->quantity}, 價格: {$product->price}, 折扣: {$product->discount}, 總金額: {$product->totalPrice}<br>";
        }
    }

    public function getTotalAmount()
    {
        $totalAmount = 0;
        foreach ($this->cart as $product) {
            $totalAmount += $product->totalPrice;
        }
        return $totalAmount;
    }
}
