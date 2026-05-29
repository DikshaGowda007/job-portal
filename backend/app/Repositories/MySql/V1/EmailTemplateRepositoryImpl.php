<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\EmailTemplate;
use App\Repositories\V1\EmailTemplateRepository;
use Illuminate\Support\Collection;

class EmailTemplateRepositoryImpl implements EmailTemplateRepository
{
    public function findByTemplateCodeAndStatus(string $templateCode, int $status): Collection
    {
        return EmailTemplate::where('template_code', $templateCode)
            ->where('status', $status)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }
}
