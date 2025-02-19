<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuportiveDocuments extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];
    public function objective()
    {
        return $this->belongsTo(ReportNarrationReport::class);
    }
}
