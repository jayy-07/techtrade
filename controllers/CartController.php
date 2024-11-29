<?php

require_once '../classes/Cart.php';

class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function addToCart($userId, $productId, $sellerId, $price, $tradeInDetails = null) {
        return $this->cart->addToCart($userId, $productId, $sellerId, $price, $tradeInDetails);
    }
}