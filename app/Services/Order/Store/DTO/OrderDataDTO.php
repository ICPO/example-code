<?php

namespace App\Services\Order\Store\DTO;

readonly class OrderDataDTO
{
    public function __construct(
        public ?int    $couponId,
        public array   $meta,
        public bool    $isPaid = false,
        public ?string $createdAt = null,
    )
    {
    }
}
