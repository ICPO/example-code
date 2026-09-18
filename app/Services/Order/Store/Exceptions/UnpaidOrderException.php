<?php

namespace App\Services\Order\Store\Exceptions;

class UnpaidOrderException extends \Exception
{
    public function __construct(private readonly string $paymentUrl)
    {
        parent::__construct('У заказа есть ссылка на оплату');
    }

    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }
}
