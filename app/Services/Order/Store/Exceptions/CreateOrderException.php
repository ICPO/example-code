<?php

namespace App\Services\Order\Store\Exceptions;

class CreateOrderException extends TechnicalStoreException
{

    public static function failed(\Throwable $previous): self
    {
        return new self(
            message: "Не удалось создать заказ: {$previous->getMessage()}",
            userMessage: 'Не удалось оформить заказ. Попробуйте позже.',
            code: 500,
            previous: $previous);
    }
}
