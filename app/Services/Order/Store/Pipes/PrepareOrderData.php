<?php

namespace App\Services\Order\Store\Pipes;

use App\Enums\DeliveryZones;
use App\Enums\OrderType;
use App\Models\Coupon;
use App\Services\Basket\BasketCouponManager;
use App\Services\DeliveryCalculator\DeliveryCalculatorService;
use App\Services\Order\Store\DTO\OrderDataDTO;
use App\Services\Order\Store\DTO\OrderPayload;
use App\Services\Order\Store\Exceptions\InvalidZoneException;
use App\Services\Order\Store\Exceptions\PrepareOrderException;
use Closure;

class PrepareOrderData
{

    public function __construct(
        protected BasketCouponManager       $basketCouponManager,
        protected DeliveryCalculatorService $deliveryCalculatorService,
    )
    {
    }

    public function handle(OrderPayload $payload, Closure $next)
    {
        try {
            $appliedCoupon = $this->basketCouponManager->getAppliedCoupon($payload->basket);

            // Рассчитываем доставку
            $deliveryPrice = $this->applyDeliveryPrice($payload);

            // Формируем meta
            $meta = $this->buildMeta($payload, $appliedCoupon, $deliveryPrice);

            $payload->orderData = new OrderDataDTO(
                couponId: $appliedCoupon?->id,
                meta: $meta,
                isPaid: false,
                createdAt: now(),
            );
        } catch (InvalidZoneException $e) {
            throw $e; 
        } catch (\Throwable $e) {
            throw PrepareOrderException::failed($e);
        }


        return $next($payload);
    }


    /**
     * Сформировать мета данные для заказа
     * @return array
     */
    private function buildMeta(OrderPayload $payload, ?Coupon $coupon, float $deliveryPrice): array
    {
        return [
            // ... example
        ];
    }

    /**
     * Рассчитать стоимость доставки
     * @return float
     */
    private function applyDeliveryPrice(OrderPayload $payload): float
    {
      // ... example
    }
}
