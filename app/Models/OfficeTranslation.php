<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['translation_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'office_translations';

    public function office()
    {
        return $this->belongsTo(Office::class, 'translation_id');
    }
}
