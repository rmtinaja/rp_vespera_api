<?php

namespace App\Domain\AutoForfeiture\Models;

use Illuminate\Database\Eloquent\Model;

class ForfeitureLine extends Model
{
    protected $table = 'mp_t_lotforfeiture_line';
    protected $primaryKey = 'mp_t_lotforfeiture_line_id';
    public $timestamps = false;

    protected $fillable = [
        'mp_t_lotforfeiture_id',
        'mp_l_preownership_id',
        'amt_overdue',
        'amt_paid',
        'date_last_payment',
        'created',
        'date_created',
        'updated',
        'date_updated',
        'is_active',
        'amt_overdue_sales',
        'amt_sales',
    ];

    // Use secondary database
    protected $connection = 'mysql_secondary';
}
