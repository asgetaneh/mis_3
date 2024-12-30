<style>
    .level-1 {
        background-color: #12cd4322;
        /* Light blue for parent */
    }

    .level-2 {
        background-color: #e6ffe6;
        /* Light green for child */
    }

    .level-3 {
        background-color: #fff8e6;
        /* Light yellow for sub-child */
    }

    .level-4 {
        background-color: #ffe6e6;
        /* Light red for sub-sub-child */
    }
</style>
{{-- level one (directores and same level) --}}
<table class="table table-bordered" style="background:#12cd4322;">
<tr>
<tr>
    <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 3 }} ">
        Offices: {{ $office->officeTranslations[0]->name }}
    </th>
    <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count()  * $planAcc->Kpi->kpiChildTwos->count()}} +4 ">
         @if (!$office->offices->isEmpty())
                <p>
                    <button class="btn btn-primary btn-expand-new" 
                        data-id="{{ $office->id }}-{{ $planAcc->Kpi->id }}" 
                        data-url="{{ url('/smis/plan/plan-accomplishment/' . $office->id . '/details/' . $planAcc->kpi->id . '/kpi/' . $planAcc->planning_year_id) }}">
                        Details
                    </button>
                </p>
            @else
                {{ 'no child ' }}
            @endif
    </td>
</tr>
<tr>
    <th colspan="2">#</th>
    <th colspan="">Baseline</th>
    @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
    <th>
            {{ $period->reportingPeriodTs[0]->name }}
        </th>
    @empty
    @endforelse
</tr>


    @forelse ($planAcc->Kpi->kpiChildOnes as $one)

    <tr>
        <th rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() }}">
            {{ $one->kpiChildOneTranslations[0]->name }}
        </th>
        @foreach ($planAcc->Kpi->kpiChildTwos as $two)
            <th>
                {{ $two->kpiChildTwoTranslations[0]->name }}
            </th>
            @php
                $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,$two->id,null);
             @endphp
            <td>{{ $baselineOfOfficePlan }}</td>
            @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                @php
                    $childAndHimOffKpi_array = [];
                    $childAndHimOffKpi = $office->offices;
                    foreach ($childAndHimOffKpi as $key => $value) {
                        $childAndHimOffKpi_array[$key] = $value->id;
                    }
                    $childAndHimOffKpi_array = array_merge($childAndHimOffKpi_array, [$office->id]);
                    //$planKpiOfficeYear = $planAcc->planSumOfKpi($planAcc->Kpi->id, $office);
                    //$planOneTwo = $planAcc->planOneTwo($planAcc->Kpi->id, $one->id, $two->id, $office, $period->id,false);
                    $planOneTwo = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id, $two->id,null);
                        $office_level = $office->level;
                    if($office_level == 0) $office_level=1;
                @endphp
                    <td>
                        @if($planOneTwo[2] <= $office_level)
                                {{ $planOneTwo[0] }} 
                        @else
                            {{0}}
                        @endif
                        <!-- {{ $planOneTwo[0] }} -->
                    </td>
                         
        @empty
            @endforelse

        </tr>
        @endforeach
    </tr>
@endforeach
    <tr>
        <th collapse="2">
            Major Activities
        </th>
        <td colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 3 }}">
            @foreach ($narration as $key => $plannaration)
                {!! html_entity_decode($plannaration->plan_naration) !!}
                @php
                    echo '<br/>';
                @endphp
            @endforeach
        </td>
    </tr>
    </table>

<script>
    const kpi_id = {{ $planAcc->kpi->id }};
    const planning_year = {{ $planAcc->planning_year_id }};
    const planAccId = {{ $planAcc->id }};
</script>
<div id="details-{{ $office->id }}-{{ $planAcc->Kpi->id }}" class="table table-bordered details-row-one" style="padding: 10px; border: 1px solid; display: none;">
    <table id="details-data-{{ $office->id }}-{{ $planAcc->Kpi->id }}" style="padding: 30px; width:100%; border: 1px solid;"></table>
</div>
<script>
   function attachNewExpandListeners() {
    // Remove existing listeners to prevent duplicate event handling
    document.querySelectorAll('.btn-expand-new').forEach(button => {
        const clone = button.cloneNode(true);
        button.parentNode.replaceChild(clone, button);
    });

    // Add new event listeners to the refreshed buttons
    document.querySelectorAll('.btn-expand-new').forEach(button => {
        button.addEventListener('click', function () {
            const officeId = this.getAttribute('data-id'); // Unique identifier for office
            const url = this.getAttribute('data-url'); // URL to fetch data
 
            if (!url) {
                console.error(`Error: URL is null or undefined for Office ID: ${officeId}`);
                return;
            }

            const detailsRow = document.getElementById(`details-${officeId}`);
            const detailsTable = document.getElementById(`details-data-${officeId}`);

            if (!detailsRow || !detailsTable) {
                console.error(`Error: Details row or table not found for Office ID: ${officeId}`);
                return;
            }

            // Toggle display and fetch data if the row is hidden
            if (detailsRow.style.display === 'none') {
                detailsTable.innerHTML = ''; // Clear previous content before fetching
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Dynamically build the table HTML
                        let tableHTML = `
                            <table class="table table-bordered" > `;
                                    
                                data.office_trans_array.forEach(office => {
                                    let myChildOneArray = [];
                                    let levelClass =  `level-${office.office_level || 1}`; // Fallback to level 1 if not defined 
                                         tableHTML += `
                                        <tr style="background:#CDCDCD;">
                                            <th colspan="${data.period_array.length + 3 }" style="width:90%;">
                                                Offices: ${office.office_name}
                                            </th>
                                            <td rowspan="${ data.parent_office_trans_array[0].kpi_child_one_count*data.parent_office_trans_array[0].kpi_child_two_count+ 3}">
                                                 ${
                                                    office.has_child
                                                        ? `<button class="btn btn-primary btn-expand-new" 
                                                                data-id="${office.id}-${office.kpi_id}" 
                                                                data-url="/smis/plan/plan-accomplishment/${office.id}/details/${office.kpi_id}/kpi/${office.pp_year}">
                                                                 Details 
                                                            </button>`
                                                        : 'No child'
                                                }
                                                <a href="/smis/plan/plan-accomplishment/${office.id}/details/${office.kpi_id}/kpi/${office.pp_year}" 
                                                    target="_blank" 
                                                    class="btn btn-link">
                                                   .
                                                </a>
                                            </td>
                                            </tr>
                                            <tr>
                                            <th colspan="2"> #</th>
                                            <th>Baseline</th> `;

                                            // Add period headers dynamically
                                             data.period_array.forEach(period => {
                                                tableHTML += `<th>${period}</th>`;
                                            });
                                             tableHTML += `</tr>`;
                                            // Populate rows for KPI children
                                            if (office.plans && Array.isArray(office.plans)) {
                                                office.plans.forEach(plan => {
                                                tableHTML += `
                                                    <tr>`; 
                                                    if (!myChildOneArray.includes(plan.kpi_child_one_name)) {
                                                        myChildOneArray.push(plan.kpi_child_one_name);
                                                        tableHTML += `
                                                             <td rowspan="${data.parent_office_trans_array[0].kpi_child_two_count}">${plan.kpi_child_one_name}</td>`;
                                                    }

                                                        tableHTML += `
                                                        <td>${plan.kpi_child_two_name}</td> 
                                                          {{-- <td rowspan=""> ${Object.keys(plan)} </td>  --}}
                                                        <td>${plan.kpi_child_baseline || 0}</td>
                                                        `; 
                                                        if (plan.plans && Array.isArray(plan.plans)) {    
                                                            plan.plans.forEach(plan2 => {
                                                                if (plan2.plan_status <= data.office_level) { // check for approval of plan
                                                                    tableHTML += `<td>${plan2.plan_value}</td>`;
                                                                } else {
                                                                    tableHTML += `<td>0</td>`; // Optional: Add an empty cell if the condition is not met
                                                                }
                                                            });
                                                        }

                                                    tableHTML += `</tr>`;
                                                });
                                            }
                                       
                                    // Add Major Activities row
                                    tableHTML += `
                                        <tr>
                                            <th>Major Activities</th> 
                                            <td colspan="${data.period_array.length + 3 }">`;
                                            
                                    // Loop through the `narration` array and append each `plan_naration` value
                                    if (office.narration && Array.isArray(office.narration)) { 
                                        office.narration.forEach(narrationn => {
                                            tableHTML += `${narrationn.plan_naration}<br>`; // Add each narration, separated by a line break
                                        });
                                    }
                                    tableHTML += `
                                            </td>
                                        </tr>
                                        <tr class="details-row" id="details-${office.id}-${office.kpi_id}" style="display: none;">
                                            <td colspan="${data.period_array.length + 3}">
                                                <table class="table table-bordered ${levelClass}">
                                                    <tbody id="details-data-${office.id}-${office.kpi_id}">
                                                        <!-- Sub-child details will be loaded here -->
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr> `; 
                                         
                                }); 
                                
                                tableHTML += `
                                    {{-- display for office it self plan --}}
                                    <tr style="background:#CDCDCD;">  `;
                                    data.parent_office_trans_array.forEach(parent_office_data_a => {
                                        tableHTML += `
                                        <th colspan="${data.period_array.length + 3 }" style="width:90%;">Offices: ${parent_office_data_a.office_name}</th>`;
                                       
                                        tableHTML += `
                                        <td rowspan="${ data.parent_office_trans_array[0].kpi_child_one_count*data.parent_office_trans_array[0].kpi_child_two_count+ 3}">
                                            Self plan
                                        </td>
                                        </tr>
                                        <tr>
                                        <th colspan="2"> #</th>
                                        <th>Baseline</th> `;
                                            // Add period headers dynamically
                                            data.period_array.forEach(period => {
                                            tableHTML += `<th>${period}</th>`;
                                        });
                                        let myChildOneParentArray = [];
                                        tableHTML += `</tr>`;
                                            // Populate rows for KPI children
                                            parent_office_data_a.planAndBaseline.forEach(kpi_child => {
                                            tableHTML += `
                                                <tr> `; 
                                                    if (!myChildOneParentArray.includes(kpi_child.kpi_child_one_name)) {
                                                        myChildOneParentArray.push(kpi_child.kpi_child_one_name);
                                                        tableHTML += `
                                                             <td rowspan="${data.parent_office_trans_array[0].kpi_child_two_count}">${kpi_child.kpi_child_one_name}</td>`;
                                                    }

                                                        tableHTML += `
                                                     <td rowspan=""> ${kpi_child.kpi_child_two_name} </td> 
                                                     <td rowspan=""> ${kpi_child.kpi_child_baseline} </td>`;
                                                    if (kpi_child.plans && Array.isArray(kpi_child.plans)) { 
                                                        kpi_child.plans.forEach(plan => {
                                                          tableHTML += `<td>${plan.plan_value}</td> 
                                                           {{-- <td rowspan=""> ${Object.keys(plan)} </td> --}}
                                                           `; 
                                                        });
                                                    }
                                                    tableHTML += `
                                                   
                                                </tr>
                                                    `; 
                                            });
                                            tableHTML += ` 
                                                <tr>
                                                <th>Major Activities</th>
                                                 ${
                                                    parent_office_data_a.narration && parent_office_data_a.narration.length > 0
                                                        ? `<td colspan="${data.period_array.length + 3 }">${parent_office_data_a.narration[0].plan_naration}</td>` // If narration exists
                                                        : `<td colspan="${data.period_array.length + 3 }">No narration available</td>` // Else
                                                }
                                                 </tr>
                                                `;  
                                             });
                            
                       `</table>`;

                        // Insert the dynamically generated table HTML
                        detailsTable.innerHTML = tableHTML;

                        // Display the details row
                        detailsRow.style.display = '';

                        // Reattach listeners for nested expand buttons
                        attachNewExpandListeners();
                    })
                    .catch(error => console.error('Fetch error:', error));
            } else {
                detailsRow.style.display = 'none'; // Hide the row if it's already displayed
            }
        });
    });

    console.log('New expand listeners attached.');
}

// Reattach listeners when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    attachNewExpandListeners();
});


</script>
