<?php

namespace App\Exceptions;

interface TechnicalException
{
    public function getLogChannel(): string;

    public function getLogLevel(): string;
}
