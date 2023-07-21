<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behavior extends Model
{
    use HasFactory;

    public function behaviorTranslations()
    {
        return $this->hasMany(BehaviorTranslation::class, 'translation_id');
    }
}
