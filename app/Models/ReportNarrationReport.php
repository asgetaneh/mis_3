<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportNarrationReport extends Model
{
    use HasFactory;
    public function SuportiveDocuments()
    {
        return $this->hasMany(SuportiveDocuments::class);
    }
}
