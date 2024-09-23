<?php

namespace App\Imports;

use App\Models\NationInstitutionId;
use Maatwebsite\Excel\Concerns\ToModel;

class IdsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {//dd($row);
        $data = NationInstitutionId::find($row['0']);
        $data2 = NationInstitutionId::find($row['1']);
       if (empty($data)) { 
       if (empty($data2)) {//dd($row['0']);
        return new NationInstitutionId([
            'nation_id'    => $row['0'], 
           'institution_id'     => $row['1'],
         ]);
        }
    }
    }
}
