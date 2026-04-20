<?php

namespace App\Constants;

class JobApplicationConstants
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_WITHDRAWN = 'WITHDRAWN';

    public const VALID_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_WITHDRAWN,
    ];

    public const CURRENCY_INR = 'INR';

    public const CURRENCY_USD = 'USD';

    public const CURRENCY_EUR = 'EUR';

    public const CURRENCY_GBP = 'GBP';

    public const VALID_CURRENCIES = [
        self::CURRENCY_INR,
        self::CURRENCY_USD,
        self::CURRENCY_EUR,
        self::CURRENCY_GBP,
    ];
}
