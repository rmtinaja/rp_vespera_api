<?php

namespace App\Domain\AutoForfeiture\DTO;

use Carbon\Carbon;

class CreateAutoForfeitureDTO
{
    public function __construct(
        public int $ad_org_id,
        public int $doc_i_submod_id,
        public string $date_trans,
        public ?string $date_gl,
        public string $docstatus,
        public string $documentno,
        public int $mp_s_owner_id,
        public int $doc_t_reference_number_id,
        public string $created,
        public string $date_created,
        public ?string $updated,
        public ?string $date_updated,
        public bool $is_active,
        public int $mp_i_owner_id,
    ) {}
}
