<table class ="table table-bordered">
    <tr>
        <td style="width:80%;">
            Offices: {{$office->officeTranslations[0]->name}}
        </td>
      
        <td> 
            @php 
                $childAndHimOffKpi_array =[];
                $childAndHimOffKpi = $office->offices; 
                foreach ($childAndHimOffKpi as $key => $value) {
                    $childAndHimOffKpi_array[$key] = $value->id;
                } 
                $childAndHimOffKpi_array = array_merge( $childAndHimOffKpi_array, array($office->id));
                  $planOfOfficePlan
                = $planAcc->planSum($planAcc->Kpi->id,$childAndHimOffKpi_array);
            @endphp
            <input name="sum"    type="number" value="{{$planOfOfficePlan}}"> 
        </td>
</tr>
</table>  
