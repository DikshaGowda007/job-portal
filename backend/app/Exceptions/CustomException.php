<?php

namespace App\Exceptions;

use App\Constants\CommonConstant;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomException extends Exception
{
    protected $errorMessage;

    protected $logType;

    public function __construct(string $errorMessage, ?Throwable $previous = null, string $logType = 'error')
    {
        $this->errorMessage = $errorMessage;
        $this->logType = $logType;
        parent::__construct($errorMessage, 0, $previous);
    }

    public function report(): void
    {
        $logData = ['exception' => $this->formatException($this)];

        match ($this->logType) {
            CommonConstant::LOG_LEVEL_INFO => Log::info($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_WARNING => Log::warning($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_DEBUG => Log::debug($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_EMERGENCY => Log::emergency($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_CRITICAL => Log::critical($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_NOTICE => Log::notice($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_ALERT => Log::alert($this->errorMessage, $logData),
            default => Log::error($this->errorMessage, $logData),
        };
    }

    private function formatException(Throwable $e)
    {
        return [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
}
