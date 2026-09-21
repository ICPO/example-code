<?php

namespace App\Services\Order\Store;

// ... example

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

        } catch (BusinessException|TechnicalException $exception) {
            throw $exception;
        } catch (UnpaidOrderException $exception) {
            return $exception->getPaymentUrl();
        } catch (\Throwable $exception) {
            throw TechnicalStoreException::common($exception);
        }

    }

}
