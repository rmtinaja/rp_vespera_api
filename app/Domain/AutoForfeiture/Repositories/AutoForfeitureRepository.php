<?php

namespace App\Domain\AutoForfeiture\Repositories;

use App\Domain\AutoForfeiture\Models\AutoForfeiture;
use App\Domain\AutoForfeiture\DTO\CreateAutoForfeitureDTO;

class AutoForfeitureRepository
{
    public function getAll()
    {
        return AutoForfeiture::where('is_active', true)->get();
    }

    public function find(int $id): ?AutoForfeiture
    {
        return AutoForfeiture::where('mp_t_lotforfeiture_id', $id)->first();
    }

    public function create(CreateAutoForfeitureDTO $dto): AutoForfeiture
    {
        return AutoForfeiture::on('mysql_secondary')->create([
            'ad_org_id'                 => $dto->ad_org_id,
            'doc_i_submod_id'           => $dto->doc_i_submod_id,
            'date_trans'                => $dto->date_trans,
            'date_gl'                   => $dto->date_gl,
            'docstatus'                 => $dto->docstatus,
            'documentno'                => $dto->documentno,
            'mp_s_owner_id'             => $dto->mp_s_owner_id,
            'doc_t_reference_number_id' => $dto->doc_t_reference_number_id,
            'created'                   => $dto->created,
            'date_created'              => $dto->date_created,
            'updated'                   => $dto->updated,
            'date_updated'              => $dto->date_updated,
            'is_active'                 => $dto->is_active,
            'mp_i_owner_id'             => $dto->mp_i_owner_id,
        ]);
    }

    public function update(AutoForfeiture $forfeiture, array $data): AutoForfeiture
    {
        $forfeiture->update($data);
        return $forfeiture;
    }

    public function delete(AutoForfeiture $forfeiture): void
    {
        $forfeiture->update([
            'is_active'    => false,
            'date_updated' => now(),
        ]);
    }

    public function countByDate(string $date): int
    {
        return AutoForfeiture::whereDate('date_created', $date)->count();
    }
}
