<?php

namespace App\Services\Order\Store\Pipes;

use App\Services\Order\Store\DTO\OrderPayload;
use App\Services\Order\Store\Exceptions\UnpaidOrderException;
use Closure;

class CheckUnpaidOrder
{
    public function handle(OrderPayload $payload, Closure $next)
    {
        $unpaidOrder = $payload->user->unpaidOrderQuery($payload->basketHash)->first();

        if ($unpaidOrder && !empty($unpaidOrder->transaction->meta['paymentURL'])) {
            throw new UnpaidOrderException($unpaidOrder->transaction->meta['paymentURL']);
        }

        return $next($payload);
    }

}
