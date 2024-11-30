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

    public function getCartItems($userId) {
        return $this->cart->getCartItems($userId);
    }

    public function getCartTotal($userId) {
        return $this->cart->getCartTotal($userId);
    }

    public function updateQuantity($cartItemId, $quantity) {
        return $this->cart->updateCartItemQuantity($cartItemId, $quantity);
    }

    public function removeItem($cartItemId) {
        return $this->cart->removeCartItem($cartItemId);
    }
}