<?php

namespace App\Constants;

class JobApplicationConstants
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_REVIEWED = 'REVIEWED';

    public const STATUS_SHORTLISTED = 'SHORTLISTED';

    public const STATUS_INTERVIEW = 'INTERVIEW';

    public const STATUS_OFFERED = 'OFFERED';

    public const STATUS_HIRED = 'HIRED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_WITHDRAWN = 'WITHDRAWN';

    public const VALID_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWED,
        self::STATUS_SHORTLISTED,
        self::STATUS_INTERVIEW,
        self::STATUS_OFFERED,
        self::STATUS_HIRED,
        self::STATUS_REJECTED,
        self::STATUS_WITHDRAWN,
    ];

    public const RECRUITER_ALLOWED_STATUSES = [
        self::STATUS_REVIEWED,
        self::STATUS_SHORTLISTED,
        self::STATUS_INTERVIEW,
        self::STATUS_OFFERED,
        self::STATUS_HIRED,
        self::STATUS_REJECTED,
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
