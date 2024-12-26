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
<table class="table table-bordered" style ="background:#CDCDCD; padding:30">
    <tr>
        <th style="width:30%">
            Offices
        </th>
        <th rowspan="">{{ 'Baseline' }}</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th>
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
        <td rowspan="3">
            @if (!$office->offices->isEmpty())
                <button class="btn btn-primary btn-expand" data-id="{{ $office->id }}"
                    data-url="{{ url('/smis/plan/plan-accomplishment/' . $office->id . '/details/' . $planAcc->kpi->id. '/kpi/' . $planAcc->planning_year_id) }}">
                    Details
                </button>
            @else
                {{ 'no child ' }}
            @endif
        </td>
    </tr>
    <tr>
        <td rowspan="" style="width:30%">{{ $office->officeTranslations[0]->name }}</td>
        @php
            $baselineOfOfficePlan = planBaseline(
                $planAcc->Kpi->id,
                $office,
                $planning_year->id,
                $period->id,
                null,
                null,
                null,
            );
        @endphp
        <td>{{ $baselineOfOfficePlan }}</td>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @php
                $planOfOfficePlan = $planAcc->KpiOTT(
                    $planAcc->Kpi->id,
                    $office,
                    $period->id,
                    false,
                    $planning_year->id ?? null,
                    null,
                    null,
                    null,
                );
                $narration = $planAcc->getNarration(
                    $planAcc->Kpi->id,
                    $planning_year->id ?? null,
                    $office,
                    $period->id,
                );
                $userOffice = auth()->user()->offices[0];
                $office_level = $userOffice->level;
                if ($office_level == 0) {
                    $office_level = 1;
                }
            @endphp
            <td>
                @if ($planOfOfficePlan[2]?->plan_status <= $office_level)
                    {{ $planOfOfficePlan[0] }}
                @else
                    {{ 0 }}
                @endif
                <!-- {{ $planOfOfficePlan[0] }} -->
            </td>
        @empty
        @endforelse
    </tr>
    <tr>
        <td>
            Major Activities
        </td>
        <td colspan="6">
            @foreach ($narration as $key => $plannaration)
                <p>
                    {!! html_entity_decode($plannaration->plan_naration) !!}
                </p>
            @endforeach
        </td>
    </tr>
</table>
<script>
    const kpi_id = {{ $planAcc->kpi->id }};
    const planning_year = {{ $planAcc->id }};
    const planAccId = {{ $planAcc->id }};
</script>
<table class="table table-bordered details-row"
      id="details-{{ $office->id }}" style="padding: 30px; width:100%; border: 1px solid; display: none;">
    <tbody id="details-data-{{ $office->id }}"  style="padding: 30px; width:100%; border: 1px solid;"> </tbody>
</table>

<script>
    function attachExpandListeners() {
        document.querySelectorAll('.btn-expand').forEach(button => {
            button.addEventListener('click', function() {
                const officeId = this.getAttribute('data-id');
                const url = this.getAttribute('data-url');

                // Check if URL is missing
                if (!url) {
                    console.error(`Error: URL is null or undefined for office ID: ${officeId}`);
                    return;
                }

                // Find the details row to expand
                const detailsRow = document.getElementById(`details-${officeId}`);

                // If no details row is found, log the error
                if (!detailsRow) {
                    console.error(`Error: Details row not found for Office ID: ${officeId}`);
                    return;
                }
                
                // If the details row is hidden, fetch the data
                if (detailsRow.style.display === 'none') {
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            const detailsTable = document.getElementById(
                            `details-data-${officeId}`);
                            detailsTable.innerHTML =
                            ''; // Clear the table before inserting new data

                            // Create table headers dynamically based on periods
                            let tableHTML = ``;


                            // Loop through office_trans_array to populate rows
                            data.office_trans_array.forEach(office => {
                                let levelClass =
                                `level-${office.office_level || 1}`; // Fallback to level 1 if not defined    
                                tableHTML += `
                             <tr >
                                {{-- <th>${office.office_level}</th> --}}
                                <th>Office Name</th>
                                <th>Baseline</th>`;

                                // Add period headers dynamically
                                data.period_array.forEach(period => {
                                    tableHTML += `<th>${period}</th>`;
                                });
                           
                                tableHTML += `<th>Actions</th></tr>
                                <tr>
                                    <td rowspan="">${office.office_name}</td>
                                     <td>${office.baseline}</td>`;

                                // Loop through plans for each office and populate plan values
                                office.plans.forEach(plan => {
                                    if (plan.plan_status <= data.office_level) { // check for approval of plan
                                        tableHTML += `<td>${plan.plan_value}</td>`;
                                    } else {
                                        tableHTML += `<td>0</td>`; // Optional: Add an empty cell if the condition is not met
                                    }
                                 });

                                // Add expand button for offices with children
                                //alert(office.pp_year);
                                tableHTML += `
                                    <td rowspan="2">
                                          ${office.has_child ? `
                                            <button class="btn btn-primary btn-expand"
                                                    data-id="${office.id}"
                                                    data-url="/smis/plan/plan-accomplishment/${office.id}/details/${office.kpi_id}/kpi/${office.pp_year}">
                                                Details
                                            </button>` 
                                        : 'No Child'}
                                    </td>
                                </tr>   `;
                                tableHTML += `
                                <tr>
                                    <td rowspan=""> Major Activities </td>
                                    <td colspan="6">`;

                                // Loop through the narrations and append each one to the table
                                office.narration.forEach(narrationn => {
                                    tableHTML +=
                                    `${narrationn.plan_naration}`; // Append each narration to the table HTML
                                });

                                tableHTML += `
                                    </td>
                                </tr>
                                <tr class="details-row" id="details-${office.id}" style="display: none;">
                                    <td colspan="${data.period_array.length + 3}">
                                        <table class="table table-bordered ${levelClass}">
                                            <tbody id="details-data-${office.id}">
                                                <!-- Sub-child details will be loaded here -->
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>`;

                            });
                            tableHTML += `
                            <tr>
                                 <th style="width:30%"> Offices</th>
                                <th  rowspan="">{{ 'Baseline' }}</th>`;
                                data.period_array.forEach(period => {
                                    tableHTML += `<th>${period}</th>`;
                                });
                                tableHTML += `<th>Actions</th>
                            </tr>
                            <tr>  `;
                            data.parent_office_data.forEach(parent_office_data_a => {
                                tableHTML += `
                                <td>${parent_office_data_a.parent_office_name}</td>
                                <td>${parent_office_data_a.baseline_self}</td>`;
                                parent_office_data_a.planOfOfficePlan_self.forEach(
                                    plans => {
                                    tableHTML += `
                                        <td>${plans[0]}</td>
                                        `;
                                });
                                tableHTML += `<td rowspan="2">self plan</td></tr>`;
                            });
                            tableHTML += `
                                <tr>
                                    <td rowspan=""> Major Activities </td>
                                    <td colspan="6">`;

                                // Loop through the narrations and append each one to the table
                                    data.narrations_self.forEach(narrationnm => {
                                        tableHTML += ` 
                                        {{-- <td rowspan=""> ${Object.keys(narrationnm[0])} </td> --}}
                                        ${narrationnm[0]?.plan_naration ?? ''}
                                        `; // Append each narration to the table HTML
                                    });

                                tableHTML += `
                                    </td>
                                </tr>` ;

                            // Insert the generated table HTML into the details section
                            detailsTable.innerHTML = tableHTML;

                            // Make the details row visible
                            detailsRow.style.display = '';

                            // Reattach listeners to new expand buttons (for sub-child offices)
                            attachExpandListeners();  
                           // alert(data);
                        })
                        .catch(error => console.error('Fetch error:', error));
                } else {
                    // Hide the details row if it was already visible
                    detailsRow.style.display = 'none';
                }
            });
        });

        console.log('Expand listeners attached to:', document.querySelectorAll('.btn-expand').length);
    }

    document.addEventListener('DOMContentLoaded', () => {
        attachExpandListeners(); // Initialize expand listeners on page load
    });
</script>
