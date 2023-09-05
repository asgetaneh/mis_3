<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportingPeriodTypeT extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['reporting_period_type_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'reporting_period_type_ts';

    public function reportingPeriodType()
    {
        return $this->belongsTo(ReportingPeriodType::class);
    }
}
