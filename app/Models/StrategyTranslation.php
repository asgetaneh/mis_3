<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StrategyTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'discription', 'translation_id'];

    protected $searchableFields = ['*'];

    protected $table = 'strategy_translations';

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'translation_id');
    }
}
