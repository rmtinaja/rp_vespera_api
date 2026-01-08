<?php

namespace App\Domain\AutomationDashboard\DTO;

use Brick\Math\BigInteger;

class CreateAutomationDashboardDTO
{
    public function __construct(
        public ?int $customer_psid,
        public ?string $conversation_status,
        public ?string $conversation_updated_from,
        public ?string $conversation_updated_to,
        public int $created_by,
        public bool $is_active = true,
    ) {}
}