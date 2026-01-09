<?php

namespace App\Domain\AutoForfeiture\Services;

use App\Domain\AutoForfeiture\DTO\CreateAutoForfeitureDTO;
use App\Domain\AutoForfeiture\Repositories\AutoForfeitureRepository;
use App\Domain\AutoForfeiture\Models\AutoForfeiture;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutoForfeitureService
{
    protected AutoForfeitureRepository $repository;

    public function __construct(AutoForfeitureRepository $repository)
    {
        $this->repository = $repository;
    }
    public function list()
    {
        return $this->repository->getAll();
    }
    public function create(array $data)
    {
        $dateTrans = Carbon::parse($data['date_trans'] ?? now())->format('Y-m-d H:i:s');
        $dateGl    = isset($data['date_gl']) ? Carbon::parse($data['date_gl'])->toDateString() : null;

        $id = DB::connection('mysql_secondary')->table('mp_t_lotforfeiture')->insertGetId([
            'ad_org_id'                 => $data['ad_org_id'],
            'doc_i_submod_id'           => $data['doc_i_submod_id'],
            'date_trans'                => $dateTrans,
            'date_gl'                   => $dateGl,
            'docstatus'                 => $data['docstatus'] ?? 'DR',
            'documentno'                =>  $data['documentno'],
            'mp_s_owner_id'             => $data['mp_s_owner_id'],
            'doc_t_reference_number_id' => $data['doc_t_reference_number_id'],
            'created'                   => 'System Auto Forfeited',
            'date_created'              => now()->format('Y-m-d H:i:s'),
            'updated'                   => $data['updated'] ?? null,
            'date_updated'              => isset($data['date_updated']) ? Carbon::parse($data['date_updated'])->format('Y-m-d H:i:s') : null,
            'is_active'                 => $data['is_active'] ?? true,
            'mp_i_owner_id'             => $data['mp_i_owner_id'],
        ]);

        return $id;  // return the newly created ID
    }
}
