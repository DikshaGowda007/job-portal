<?php

namespace App\Repositories\V1;

use Illuminate\Support\Collection;

interface EmailTemplateRepository
{
    public function findByTemplateCodeAndStatus(string $templateCode, int $status): Collection;
}
