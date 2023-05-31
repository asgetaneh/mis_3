<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerspectiveTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'translation_id'];

    protected $searchableFields = ['*'];

    protected $table = 'perspective_translations';

    public function perspective()
    {
        return $this->belongsTo(Perspective::class, 'translation_id');
    }
}
