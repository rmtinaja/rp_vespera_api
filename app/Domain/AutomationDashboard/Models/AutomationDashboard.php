<?php

namespace App\Domain\AutomationDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationDashboard extends Model
{
    use HasFactory;

    protected $table = 'wbs_i_transitionconversation_logs';

    protected $primaryKey = 'conversation_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'conversation_id',
        'customer_psid',
        'conversation_status',
        'conversation_updated_from',
        'conversation_updated_to',
        'is_active',
        'created_by',
        'date_created',
    ];
}