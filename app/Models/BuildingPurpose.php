<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;

class BuildingPurpose extends Model
{
    use HasFactory;
    use Searchable;
}
