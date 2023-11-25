<?php

namespace App\Models;
use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use Searchable;
    public function kpi()
    {
        return $this->hasMany(KeyPeformanceIndicator::class, 'kpi_id');
    }
    public function office()
    {
        return $this->hasMany(Office::class, 'office_id');
    }
    public function period()
    {
        return $this->hasMany(ReportingPeriod::class, 'reporting_period');
    }
}
