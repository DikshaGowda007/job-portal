<?php

namespace App\Constants;

class JobSeekerProfileConstants
{
    public const EMPLOYMENT_TYPE_FULL_TIME = 'FULL_TIME';

    public const EMPLOYMENT_TYPE_PART_TIME = 'PART_TIME';

    public const EMPLOYMENT_TYPE_CONTRACT = 'CONTRACT';

    public const EMPLOYMENT_TYPE_INTERNSHIP = 'INTERNSHIP';

    public const EMPLOYMENT_TYPE_FREELANCE = 'FREELANCE';

    public const VALID_EMPLOYMENT_TYPES = [
        self::EMPLOYMENT_TYPE_FULL_TIME,
        self::EMPLOYMENT_TYPE_PART_TIME,
        self::EMPLOYMENT_TYPE_CONTRACT,
        self::EMPLOYMENT_TYPE_INTERNSHIP,
        self::EMPLOYMENT_TYPE_FREELANCE,
    ];

    public const WORK_MODE_ONSITE = 'ONSITE';

    public const WORK_MODE_REMOTE = 'REMOTE';

    public const WORK_MODE_HYBRID = 'HYBRID';

    public const VALID_WORK_MODES = [
        self::WORK_MODE_ONSITE,
        self::WORK_MODE_REMOTE,
        self::WORK_MODE_HYBRID,
    ];
}
