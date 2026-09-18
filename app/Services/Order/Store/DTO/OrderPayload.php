<?php

namespace App\Services\Order\Store\DTO;

use App\DTO\Basket\BasketDTO;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;

class OrderPayload
{
    /**
     * Контекст пайплайна создания заказа.
     *
     * Иммутабельные: $user, $validatedData, $basket.
     * Мутабельные: $basketHash, $orderData, $order, $orderWasCreated, $transaction.
     */
    public function __construct(
        public readonly User      $user,
        public readonly array     $validatedData,
        public readonly BasketDTO $basket,
        public ?string            $basketHash = null,
        public ?OrderDataDTO      $orderData = null,
        public ?Order             $order = null,
        public bool               $orderWasCreated = false,
        public ?Transaction       $transaction = null,
    )
    {
    }

}
