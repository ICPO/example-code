<?php

namespace App\Exceptions;

interface BusinessException
{
    public function getLogLevel(): string;

}
