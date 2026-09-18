<?php

namespace App\Services\Order\Store;

use App\Models\User;
use App\Services\Basket\BasketService;
use App\Services\Order\Store\DTO\OrderPayload;
use App\Services\Order\Store\Exceptions\TechnicalStoreException;
use App\Services\Order\Store\Exceptions\UnpaidOrderException;
use App\Services\Order\Store\Pipes\AwardGift;
use App\Services\Order\Store\Pipes\CheckUnpaidOrder;
use App\Services\Order\Store\Pipes\CreateOrder;
use App\Services\Order\Store\Pipes\CreateOrderItems;
use App\Services\Order\Store\Pipes\CreateTransaction;
use App\Services\Order\Store\Pipes\PrepareOrderData;
use App\Services\TBank\TBank;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OrderProcessOrchestrator
{


    public function __construct(protected Pipeline $pipeline, protected BasketService $basketService, protected TBank $TBank)
    {
    }


    public function process(User $user, array $validatedData): string
    {
        try {
            $payload = new OrderPayload($user, $validatedData, $this->basketService->getBasket($user));

            // Транзакция 1. Критичные пайпы
            [$order, $transaction] = DB::transaction(function () use ($payload) {
                return $this->pipeline->send($payload)->through([
                    /// ... example
                    CheckUnpaidOrder::class,
                    PrepareOrderData::class,
                    CreateOrder::class,
                    CreateOrderItems::class,
                    CreateTransaction::class,
                    /// ... example
                ])->then(fn() => [$payload->order, $payload->transaction]);
            });

            // Транзакция 2: некритичные пайпы
            DB::transaction(function () use ($payload) {
                return $this->pipeline->send($payload)->through([
                    AwardGift::class,
                ]);
            });

            return $this->TBank->getPaymentUrl($transaction, $order, $user);

        } catch (UnpaidOrderException $exception) {
            return $exception->getPaymentUrl();
        } catch (\Throwable $exception) {
            throw TechnicalStoreException::common($exception);
        }

    }

}
