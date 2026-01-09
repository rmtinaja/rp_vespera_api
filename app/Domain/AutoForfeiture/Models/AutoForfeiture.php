<?php

namespace App\Domain\AutoForfeiture\Models;

use Illuminate\Database\Eloquent\Model;

class AutoForfeiture extends Model
{
    protected $table = 'mp_t_lotforfeiture';

    protected $primaryKey = 'mp_t_lotforfeiture_id';

    public $timestamps = false;

    protected $fillable = [
        'ad_org_id',
        'doc_i_submod_id',
        'date_trans',
        'date_gl',
        'docstatus',
        'documentno',
        'mp_s_owner_id',
        'doc_t_reference_number_id',
        'created',
        'date_created',
        'updated',
        'date_updated',
        'is_active',
        'mp_i_owner_id',
    ];

     protected $connection = 'mysql_secondary';
}
