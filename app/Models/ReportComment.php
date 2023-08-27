<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'report_comment',
        'kpi_id',
        'reporting_period_id',
        'planning_year_id',
        'office_id',
        'status',
        'reply_comment',
        'commented_by',
        'replied_active'
    ];

    protected $searchableFields = ['*'];

    protected $table = 'report_comments';

    // public function planAccomplishments()
    // {
    //     return $this->belongsTo(PlanAccomplishment::class);
    // }
}
