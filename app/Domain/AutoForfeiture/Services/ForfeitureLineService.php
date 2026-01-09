<?php

namespace App\Domain\AutoForfeiture\Services;

use App\Domain\AutoForfeiture\DTO\CreateForfeitureLineDTO;
use App\Domain\AutoForfeiture\Repositories\ForfeitureLineRepository;
use Illuminate\Support\Facades\DB;

class ForfeitureLineService
{
    protected ForfeitureLineRepository $repository;

    public function __construct(ForfeitureLineRepository $repository)
    {
        $this->repository = $repository;
    }
    public function list()
    {
        return $this->repository->getAll();
    }
    public function create(array $data): int
    {
        $id = DB::connection('mysql_secondary')->table('mp_t_lotforfeiture_line')->insertGetId([
            'mp_t_lotforfeiture_id' => $data['mp_t_lotforfeiture_id'] ?? null,
            'mp_l_preownership_id'  => $data['mp_l_preownership_id'] ?? null,
            'amt_overdue'           => $data['amt_overdue'] ?? 0,
            'amt_paid'              => $data['amt_paid'] ?? 0,
            'date_last_payment'     => $data['date_last_payment'] ?? null,
            'created'               => $data['created'] ?? 'System Auto Forfeited',
            'date_created'          => $data['date_created'] ?? now()->format('Y-m-d H:i:s'),
            'updated'               => $data['updated'] ?? null,
            'date_updated'          => $data['date_updated'] ?? null,
            'is_active'             => $data['is_active'] ?? true,
            'amt_overdue_sales'     => $data['amt_overdue_sales'] ?? 0,
            'amt_sales'             => $data['amt_sales'] ?? 0,
        ]);

        return $id;
    }
}
