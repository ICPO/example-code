<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderStoreRequest;
use App\Services\Basket\BasketService;
use App\Services\Order\Store\OrderProcessOrchestrator;
// ............ example

class OrderController extends Controller
{
    public function __construct(
        protected OrderProcessOrchestrator $orchestrator,
        protected BasketService            $basketService,
    )
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreRequest $request)
    {
        $paymentUrl = $this->orchestrator->process($request->user(), $request->validated());
        $this->basketService->clear($request->user());
        return inertia_location($paymentUrl);
    }


  // ............ example
}
