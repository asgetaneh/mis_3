<table class ="table table-bordered">
    <tr>
        <td style="width:80%;">
            Offices: {{$office->officeTranslations[0]->name}}
        </td>
      
        <td> 
            @php 
                $planOfOfficePlan
                = $planAcc->planSum($planAcc->Kpi->id,$office->id);
            @endphp
            <input name="sum"    type="number" value="{{$planOfOfficePlan}}"> 
        </td>
</tr>
</table>  
