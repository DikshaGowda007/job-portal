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

        $channel = Log::channel('stderr');

        match ($this->logType) {
            CommonConstant::LOG_LEVEL_INFO => $channel->info($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_WARNING => $channel->warning($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_DEBUG => $channel->debug($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_EMERGENCY => $channel->emergency($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_CRITICAL => $channel->critical($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_NOTICE => $channel->notice($this->errorMessage, $logData),
            CommonConstant::LOG_LEVEL_ALERT => $channel->alert($this->errorMessage, $logData),
            default => $channel->error($this->errorMessage, $logData),
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
