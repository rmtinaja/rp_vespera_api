<?php

namespace App\Domain\Issues\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
    use HasFactory;

    protected $table = 'wbs_i_issue_information';
    protected $primaryKey = 'issue_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'issue_id',
        'concern_title',
        'concern_description',
        'assignee_id',
        'status_priority',
        'date_issues',
        'date_start',
        'date_hold',
        'date_complete',
        'date_resume',
        'is_active',
        'created_by',
        'date_created',
        'ticket_status',
        'ticket_no',
    ];
}