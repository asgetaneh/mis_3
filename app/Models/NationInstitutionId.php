<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;


class NationInstitutionId extends Model
{
    use HasFactory;
    use Searchable;
    protected $fillable = ['institution_id', 'nation_id'];


    protected $searchableFields = ['*'];
     
    public function  getNationalId($student_id){  
        $NationInstitutionId = NationInstitutionId::select('nation_id')
        ->where('institution_id' , '=', $student_id)
         ->get();
        return $NationInstitutionId;
     }
}
