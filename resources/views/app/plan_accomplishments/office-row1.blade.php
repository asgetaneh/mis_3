<style>
    .level-1 {
        background-color: #f0f8ff;
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
<table class="table table-bordered" style ="background:#CDCDCD; padding:30">
    <tr>
        <th colspan="{{ getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 2 }} " style="width:90%">
            Offices: {{ $office->officeTranslations[0]->name }}
        </th>
         <td rowspan="{{ $planAcc->Kpi->kpiChildOnes->count() + 3 }}">
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
        <th>#</th>
        <th  rowspan="">{{"Baseline"}}</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse

        @foreach ($planAcc->Kpi->kpiChildOnes as $one)
    <tr>
        <td>
            {{ $one->kpiChildOneTranslations[0]->name }}
        </td>
        @php
            $baselineOfOfficePlan  = planBaseline($planAcc->Kpi->id,$office, $planning_year->id, $period->id,$one->id,null,null);
            //dump($office);
        @endphp
        <td>
            {{ $baselineOfOfficePlan }}
        </td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <td>
                @php
                   // $planOne = $planAcc->planOne($planAcc->Kpi->id, $one->id, $office, $period->id,false);
                    $planOne = $planAcc->KpiOTT($planAcc->Kpi->id, $office, $period->id,false,$planning_year->id ?? NULL ,$one->id,null,null);
                    $narration = $planAcc->getNarration($planAcc->Kpi->id, $planning_year->id ?? NULL, $office, $period->id);
                    $office_level = $office->level;
                    if($office_level == 0) $office_level=1;
                    //dd($planOne[2]?->plan_status);
                @endphp
                @if($planOne[2]?->plan_status <= $office_level)
                     {{ $planOne[0] }} 
                @else
                    {{0}}
                @endif
                 <!-- {{ $planOne[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    @endforeach
    <tr>
        <th>
            Major Activities
        </th>
        <td colspan="{{count(getQuarter($planAcc->Kpi->reportingPeriodType->id))+1}}">
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
                                    let levelClass =  `level-${office.office_level || 1}`; // Fallback to level 1 if not defined 
                                         tableHTML += `
                                        <tr style="background:#CDCDCD;">
                                            <th colspan="${data.period_array.length + 2 }" style="width:90%;">
                                                Offices: ${office.office_name}
                                            </th>
                                            <td rowspan="${office.plans.length + 3}">
                                                ${
                                                    office.has_child
                                                        ? `<button class="btn btn-primary btn-expand-new" 
                                                                data-id="${office.id}-${office.kpi_id}" 
                                                                data-url="/smis/plan/plan-accomplishment/${office.id}/details/${office.kpi_id}/kpi/${office.pp_year}">
                                                                 Details 
                                                            </button>`
                                                        : 'No child'
                                                }
                                            </td>
                                            </tr>
                                            <tr>
                                            <th> #</th>
                                            <th>Baseline</th> `;

                                            // Add period headers dynamically
                                             data.period_array.forEach(period => {
                                                tableHTML += `<th>${period}</th>`;
                                            });
                                             tableHTML += `</tr>`;
                                            // Populate rows for KPI children
                                            office.plans.forEach(plan => {
                                            tableHTML += `
                                                <tr>
                                                    <td>${plan.kpi_child_name}</td> 
                                                     {{--   
                                                      <td rowspan=""> ${Object.keys(plan.kpi_child_baseline)} </td> --}}
                                                     <td>${plan.kpi_child_baseline || 0}</td>
                                                    `; 
                                                                  
                                                     plan.plans.forEach(plan2 => {
                                                        if (plan2.plan_status <= data.office_level) { // check for approval of plan
                                                            tableHTML += `<td>${plan2.plan_value}</td>`;
                                                        } else {
                                                            tableHTML += `<td>0</td>`; // Optional: Add an empty cell if the condition is not met
                                                        }
                                                    });

                                                tableHTML += `</tr>`;
                                            });
                                       
                                    // Add Major Activities row
                                    tableHTML += `
                                        <tr>
                                            <th>Major Activities</th> 
                                            <td colspan="${data.period_array.length + 1}">`;
                                            
                                    // Loop through the `narration` array and append each `plan_naration` value
                                    office.narration.forEach(narrationn => {
                                        tableHTML += `${narrationn.plan_naration}<br>`; // Add each narration, separated by a line break
                                    });

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
                                        <th colspan="${data.period_array.length + 2 }" style="width:90%;">Offices: ${parent_office_data_a.office_name}</th>`;
                                       
                                        tableHTML += `
                                        <td rowspan="${4 + 3}">
                                            Self plan
                                        </td>
                                        </tr>
                                        <tr>
                                        <th> #</th>
                                        <th>Baseline</th> `;
                                            // Add period headers dynamically
                                            data.period_array.forEach(period => {
                                            tableHTML += `<th>${period}</th>`;
                                        });
                                        tableHTML += `</tr>`;
                                            // Populate rows for KPI children
                                            parent_office_data_a.planAndBaseline.forEach(kpi_child => {
                                            tableHTML += `
                                                <tr>
                                                     <td rowspan=""> ${kpi_child.kpi_child_name} </td>
                                                     <td rowspan=""> ${kpi_child.kpi_child_baseline} </td> `;
                                                     kpi_child.plans.forEach(plan => {
                                                          tableHTML += `<td>${plan.plan_value}</td> 
                                                           {{-- <td rowspan=""> ${Object.keys(plan)} </td> --}}
                                                           `; 
                                                          });
                                                    tableHTML += `
                                                   
                                                </tr>
                                                    `; 
                                            });
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
