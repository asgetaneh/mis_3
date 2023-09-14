<?php

namespace App\Models;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{
   use HasFactory;
    use Searchable;

    protected $fillable = [
        'office_id',
        'user_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'performers';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
     public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
