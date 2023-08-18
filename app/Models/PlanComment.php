<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanComment extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'plan_comment',
        'kpi_id',
        'reporting_period_id',
        'planning_year_id',
        'office_id',
        'status',
        'commented_by'
    ];

    protected $searchableFields = ['*'];

    protected $table = 'plan_comments';

    // public function planAccomplishments()
    // {
    //     return $this->belongsTo(PlanAccomplishment::class);
    // }
}
