<?php

namespace App\Services\Order\Store\Pipes;

use App\Models\Order;
use App\Services\Order\Store\DTO\OrderPayload;
use App\Services\Order\Store\Exceptions\CreateOrderException;
use Closure;

class CreateOrder
{

    public function handle(OrderPayload $payload, Closure $next)
    {
        try {
            $payload->order = Order::create([
                'user_id' => $payload->user->id,
                'coupon_id' => $payload->orderData->couponId,  
                'meta' => $payload->orderData->meta,
                // ... example
            ]);
            $payload->orderWasCreated = true;
        } catch (\Throwable $exception) {
            throw CreateOrderException::failed($exception);
        }

        return $next($payload);
    }
}
