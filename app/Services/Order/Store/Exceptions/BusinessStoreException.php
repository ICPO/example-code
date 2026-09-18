<?php

namespace App\Services\Order\Store\Exceptions;

use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BusinessStoreException extends \Exception implements BusinessException
{

    public function getLogLevel(): string
    {
        return 'info';
    }

    public function __construct(
        string                  $message,
        private readonly string $userMessage,
        int                     $code = 0,
        ?\Throwable             $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function render(Request $request): RedirectResponse
    {
        return back()->withErrors(['error' => $this->getUserMessage()]);
    }
}
