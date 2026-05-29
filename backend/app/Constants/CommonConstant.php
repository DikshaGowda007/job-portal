<?php

namespace App\Constants;

class CommonConstant
{
    public const ERROR = 'error';

    public const SUCCESS = 'success';

    public const UNAUTHORIZED_EXCEPTION_MESSAGE = 'Unauthorized.';

    public const IS_VERIFIED_USER = 1;

    public const OTP_SENT_SUCCESS = 'OTP Sent successfully';

    public const OTP_SENT_FAIL = 'Failed to send OTP';

    public const TOKEN_NOT_PROVIDED = 'Token not provided';

    public const UNAUTHORIZED_EXCEPTION_CODE = 401;

    public const OTP_EXPIRED = 'Your OTP has expired. Please request a new one.';

    public const LOG_LEVEL_DEBUG = 'debug';

    public const LOG_LEVEL_INFO = 'info';

    public const LOG_LEVEL_NOTICE = 'notice';

    public const LOG_LEVEL_WARNING = 'warning';

    public const LOG_LEVEL_ERROR = 'error';

    public const LOG_LEVEL_CRITICAL = 'critical';

    public const LOG_LEVEL_ALERT = 'alert';

    public const LOG_LEVEL_EMERGENCY = 'emergency';

    public const STATUS_ACTIVE = 1;

    public const STATUS_INACTIVE = 0;

    public const STATUS_BLOCKED = 2;

    public const IS_DELETED_YES = 1;

    public const IS_DELETED_NO = 0;
}
