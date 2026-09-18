<?php

namespace App\Services\Order\Store\Exceptions;

use App\Exceptions\TechnicalException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TechnicalStoreException extends \Exception implements TechnicalException
{

    public function getLogChannel(): string
    {
        return 'orderStore';
    }

    public function getLogLevel(): string
    {
        return 'critical';
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

    public static function common(\Throwable $previous): self
    {
        return new self(
            message: "Возникла общая техническая ошибка: {$previous->getMessage()}",
            userMessage: 'Не удалось оформить заказ. Попробуйте позже.',
            code: 500,
            previous: $previous
        );
    }

    public function render(Request $request): RedirectResponse
    {
        return back()->withErrors(['error' => $this->getUserMessage()]);
    }

}
