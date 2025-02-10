<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['holder_id', 'parent_office_id', 'level'];

    protected $searchableFields = ['*'];

    public function user()
    {
        return $this->belongsTo(User::class, 'holder_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'parent_office_id');
    }

    public function offices()
    {
        return $this->hasMany(Office::class, 'parent_office_id');
    }

    public function officeTranslations()
    {
        return $this->hasMany(OfficeTranslation::class, 'translation_id');
    }

    public function suitableKpis()
    {
        return $this->hasMany(SuitableKpi::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'manager');
    }

    public function keyPeformanceIndicators()
    {
        return $this->belongsToMany(
            KeyPeformanceIndicator::class,
            'kpi_office',
            'office_id',
            'kpi_id'
        );
    }
    public function performers()
    {
        return $this->hasMany(Performer::class, 'office_id');
    }
    public function childs(){
        return $this->hasMany(Office::class, 'parent_office_id')->with('childs');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
