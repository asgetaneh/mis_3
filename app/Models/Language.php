<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'locale'];

    protected $searchableFields = ['*'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'created_by_id');
    // }
}
