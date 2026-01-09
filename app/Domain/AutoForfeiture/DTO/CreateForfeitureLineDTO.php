<?php

namespace App\Domain\AutoForfeiture\DTO;

class CreateForfeitureLineDTO
{
    public function __construct(
        public ?int $mp_t_lotforfeiture_id,
        public ?int $mp_l_preownership_id,
        public ?float $amt_overdue,
        public ?float $amt_paid,
        public ?string $date_last_payment,
        public ?string $created,
        public ?string $date_created,
        public ?string $updated,
        public ?string $date_updated,
        public ?bool $is_active,
        public ?float $amt_overdue_sales,
        public ?float $amt_sales,
    ) {}
}
