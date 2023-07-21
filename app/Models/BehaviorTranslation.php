<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehaviorTranslation extends Model
{
    use HasFactory;

    protected $table = 'behavior_translations';
    public function behavior()
    {
        return $this->belongsTo(Behavior::class, 'translation_id');
    }
}
