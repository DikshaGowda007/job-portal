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

    public function report()
    {
        if ($this->logType == CommonConstant::LOG_LEVEL_ERROR) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->error($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_INFO) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->info($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_WARNING) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->warning($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_DEBUG) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->debug($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_EMERGENCY) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->emergency($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_CRITICAL) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->critical($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_NOTICE) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->notice($this->errorMessage, ['exception' => $this->formatException($this)]);
        } elseif ($this->logType == CommonConstant::LOG_LEVEL_ALERT) {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->alert($this->errorMessage, ['exception' => $this->formatException($this)]);
        } else {
            Log::channel(env('DEFAULT_LOG_CHANNEL'))->error($this->errorMessage, ['exception' => $this->formatException($this)]);
        }
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
