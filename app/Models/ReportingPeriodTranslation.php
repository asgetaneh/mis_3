<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportingPeriodTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'reporting_period_id'];

    protected $searchableFields = ['*'];

    protected $table = 'reporting_period_translations';

    public function reportingPeriod()
    {
        return $this->belongsTo(ReportingPeriod::class);
    }
}
