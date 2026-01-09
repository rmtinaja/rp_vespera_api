<?php

namespace App\Domain\AutoForfeiture\Repositories;

use App\Domain\AutoForfeiture\DTO\CreateForfeitureLineDTO;
use App\Domain\AutoForfeiture\Models\ForfeitureLine;
use Illuminate\Support\Facades\DB;

class ForfeitureLineRepository
{
        public function getAll()
    {
        return ForfeitureLine::where('is_active', true)->get();
    }

    public function find(int $id): ?ForfeitureLine
    {
        return ForfeitureLine::where('mp_t_lotforfeiture_line_id', $id)->first();
    }
    public function create(CreateForfeitureLineDTO $dto): int
    {
        return DB::connection('mysql_secondary')->table('mp_t_lotforfeiture_line')->insertGetId([
            'mp_t_lotforfeiture_id' => $dto->mp_t_lotforfeiture_id,
            'mp_l_preownership_id'  => $dto->mp_l_preownership_id,
            'amt_overdue'           => $dto->amt_overdue ?? 0,
            'amt_paid'              => $dto->amt_paid ?? 0,
            'date_last_payment'     => $dto->date_last_payment,
            'created'               => $dto->created ?? 'System Auto Forfeited',
            'date_created'          => $dto->date_created ?? now()->format('Y-m-d H:i:s'),
            'updated'               => $dto->updated,
            'date_updated'          => $dto->date_updated ?? null,
            'is_active'             => $dto->is_active ?? true,
            'amt_overdue_sales'     => $dto->amt_overdue_sales ?? 0,
            'amt_sales'             => $dto->amt_sales ?? 0,
        ]);
    }
}
