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
<table class="table table-bordered" style="background:#12cd4322;">
    @php
        $ospan =
            $planAcc->Kpi->kpiChildThrees->count() * (getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 1) +
            2;
    @endphp
    <!-- <tr id="child-ones"> -->
    <tr>

        <th colspan="{{ $ospan }} " style="background:#fff7e6;width:100%">
            Offices: {{ $office->officeTranslations[0]->name }}
            </td>
        <td
            rowspan="{{ $planAcc->Kpi->kpiChildTwos->count() * count(getQuarter($planAcc->Kpi->reportingPeriodType->id)) + 4 }}">
            @if (!$office->offices->isEmpty())
                <p>
                    <button class="btn btn-primary btn-expand-new-two"
                        data-id="{{ $office->id }}-two-{{ $planAcc->Kpi->id }}"
                        data-url="{{ url('/smis/plan/plan-accomplishment/office/' . $office->id . '/kpi/' . $planAcc->kpi->id . '/year/' . $planAcc->planning_year_id) }}">
                        Details
                        {{-- {{$office->id . '-' . $planAcc->kpi->id }} --}}
                    </button>
                </p>
            @else
                {{ '' }}
            @endif
        </td>
    </tr>


    <tr>
        <th rowspan="2" colspan="2">#</th>
        <th colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">Baseline</th>
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            <th colspan="{{ $planAcc->Kpi->kpiChildThrees->count() }}">
                {{ $period->reportingPeriodTs[0]->name }}
            </th>
        @empty
        @endforelse
    </tr>

    <tr>
        @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
            <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }} </th>
        @endforeach
        @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
            @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                <th>{{ $kpiThree->kpiChildThreeTranslations[0]->name }}
                </th>
            @endforeach
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
                @foreach ($planAcc->Kpi->kpiChildThrees as $three)
                    @php
                        $baselineOfOfficePlan = planBaseline(
                            $planAcc->Kpi->id,
                            $office,
                            $planning_year->id,
                            $period->id,
                            $one->id,
                            $two->id,
                            $three->id,
                        );
                    @endphp
                    <td>{{ $baselineOfOfficePlan }}</td>
                @endforeach


                @forelse(getQuarter($planAcc->Kpi->reportingPeriodType->id) as $period)
                    @php
                        $childAndHim_array = [];
                    @endphp
                    @foreach ($planAcc->Kpi->kpiChildThrees as $kpiThree)
                        <td>
                            @php
                                $childAndHim = $office->offices;
                                foreach ($childAndHim as $key => $value) {
                                    $childAndHim_array[$key] = $value->id;
                                }
                                $childAndHim_array = array_merge($childAndHim_array, [$office->id]);
                                // $plan123 = $planAcc->planIndividual($planAcc->Kpi->id, $one->id, $two->id, $kpiThree->id, $office, $period->id,false);
                                $plan123 = $planAcc->KpiOTT(
                                    $planAcc->Kpi->id,
                                    $office,
                                    $period->id,
                                    false,
                                    $planning_year->id ?? null,
                                    $one->id,
                                    $two->id,
                                    $kpiThree->id,
                                );
                                $narration = $planAcc->getNarration(
                                    $planAcc->Kpi->id,
                                    $planning_year->id ?? null,
                                    $office,
                                    $period->id,
                                );
                                $office_level = $office->level;
                                if ($office_level == 0) {
                                    $office_level = 1;
                                }

                            @endphp
                            @if ($plan123[2] <= $office_level)
                                {{ $plan123[0] }}
                            @else
                                {{ 0 }}
                            @endif
                            <!-- {{ $plan123[0] }} -->
                        </td>
                    @endforeach
                @empty
                @endforelse

        </tr>
    @endforeach

    @endforeach
    <tr>
        <td>
            Major Activities
        </td>
        <td
            colspan="{{ (getQuarter($planAcc->Kpi->reportingPeriodType->id)->count() + 1) * $planAcc->Kpi->kpiChildThrees->count() + 1 }}">
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
    {{-- const kpi_id = {{ $planAcc->kpi->id }};
    const planning_year = {{ $planAcc->planning_year_id }};
    const planAccId = {{ $planAcc->id }}; --}}
</script>
<div id="details-{{ $office->id }}-two-{{ $planAcc->Kpi->id }}" class="table table-bordered details-row-one"
    style="padding: 10px; border: 1px solid; display: none;">
    <table id="details-data-{{ $office->id }}-two-{{ $planAcc->Kpi->id }}"
        style="padding: 20px; width:100%; border: 1px solid;"></table>
</div>
<script>
    function attachNewExpandListeners() {
        // Remove existing listeners to prevent duplicate event handling
        document.querySelectorAll('.btn-expand-new-two').forEach(button => {
            const clone = button.cloneNode(true);
            button.parentNode.replaceChild(clone, button);
        });

        // Add new event listeners to the refreshed buttons
        document.querySelectorAll('.btn-expand-new-two').forEach(button => {
            button.addEventListener('click', function() {
                const officeIdTwo = this.getAttribute('data-id'); // Unique identifier for office
                const url = this.getAttribute('data-url'); // URL to fetch data

                if (!url) {
                    console.error(`Error: URL is null or undefined for Office ID: ${officeIdTwo}`);
                    return;
                }

                const detailsRow = document.getElementById(`details-${officeIdTwo}`);
                const detailsTable = document.getElementById(`details-data-${officeIdTwo}`);

                if (!detailsRow || !detailsTable) {
                    console.error(
                        `Error: Details row or table not found for Office ID: ${officeIdTwo}`);
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
                                let levelClass =
                                    `level-${office.office_level || 1}`; // Fallback to level 1 if not defined
                                tableHTML += `
                                        <tr style="background:#CDCDCD;">
                                            {{-- <td rowspan=""> ${Object.keys(data.office_trans_array)} </td> --}}
                                            <th colspan="${(data.period_array.length + 3)*data.parent_office_trans_array[0].kpi_child_three_count }" style="width:90%;">
                                                Offices: ${office.office_name}
                                            </th>
                                            <td rowspan="${ data.parent_office_trans_array[0].kpi_child_one_count*data.parent_office_trans_array[0].kpi_child_two_count+ 3}">
                                                 ${
                                                    office.has_child
                                                        ? `<button class="btn btn-primary btn-expand-new-two"
                                                                data-id="${office.id}-two-${office.kpi_id}"
                                                                data-url="/smis/plan/plan-accomplishment/office/${office.id}/kpi/${office.kpi_id}/year/${office.pp_year}">
                                                                 Details
                                                                 {{-- ${office.id}-${office.kpi_id} --}}
                                                            </button>`
                                                        : ''
                                                }
                                                <a href="/smis/plan/plan-accomplishment/office/${office.id}/kpi/${office.kpi_id}/year/${office.pp_year}"
                                                    target="_blank"
                                                    class="btn btn-link">
                                                   .
                                                </a>
                                            </td>
                                            </tr>
                                            <tr>
                                            <th colspan="2" rowspan="2"> #</th>
                                            <th colspan="2">Baseline</th> `;

                                // Add period headers dynamically
                                data.period_array.forEach(period => {
                                    tableHTML += `<th colspan="3">${period}</th>`;
                                });
                                tableHTML += `</tr>`;
                                if (parent_office_data_a.planAndBaseline && Array.isArray(
                                        parent_office_data_a.planAndBaseline)) {
                                    parent_office_data_a.planAndBaseline.forEach(
                                    ch_three => {
                                        tableHTML += ` <tr>`;
                                        if (!myChildOneArray.includes(ch_three
                                                .kpi_child_three_name)) {
                                            myChildOneArray.push(ch_three
                                                .kpi_child_three_name);
                                            tableHTML +=
                                                `  <td rowspan="">${ch_three.kpi_child_three_name}</td>`;
                                        }
                                    });
                                }
                                tableHTML += `</tr>`;

                                tableHTML += `</tr>`;
                                // Populate rows for KPI children

                                if (office.plans && Array.isArray(office.plans)) {
                                    office.plans.forEach(plan => {
                                        tableHTML += `
                                                    <tr>`;
                                        if (!myChildOneArray.includes(plan
                                                .kpi_child_one_name)) {
                                            myChildOneArray.push(plan
                                                .kpi_child_one_name);
                                            tableHTML +=
                                                `
                                                <td rowspan="${data.parent_office_trans_array[0].kpi_child_two_count}">${plan.kpi_child_one_name}</td>`;
                                        }

                                        tableHTML += `
                                                        <td>${plan.kpi_child_two_name}</td>
                                                         <td>${plan.kpi_child_baseline || 0}</td> `;
                                        if (plan.plans && Array.isArray(plan
                                                .plans)) {
                                            plan.plans.forEach(plan2 => {
                                                if (plan2.plan_status <=
                                                    data.office_level
                                                ) { // check for approval of plan
                                                    tableHTML +=
                                                        `<td>${plan2.plan_value}</td>`;
                                                } else {
                                                    tableHTML +=
                                                        `<td>0</td>`; // Optional: Add an empty cell if the condition is not met
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
                                        tableHTML +=
                                            `${narrationn.plan_naration}<br>`; // Add each narration, separated by a line break
                                    });
                                }
                                tableHTML += `
                                            </td>
                                        </tr>
                                        <tr class="details-row" id="details-${office.id}-two-${office.kpi_id}" style="display: none;">
                                            <td colspan="${data.period_array.length + 4}">
                                                <table class="table table-bordered ${levelClass}">
                                                    <tbody id="details-data-${office.id}-two-${office.kpi_id}">
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
                                tableHTML +=
                                    `
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
                                    if (!myChildOneParentArray.includes(kpi_child
                                            .kpi_child_one_name)) {
                                        myChildOneParentArray.push(kpi_child
                                            .kpi_child_one_name);
                                        tableHTML +=
                                            `
                                                             <td rowspan="${data.parent_office_trans_array[0].kpi_child_two_count}">${kpi_child.kpi_child_one_name}</td>`;
                                    }

                                    tableHTML += `
                                                     <td rowspan=""> ${kpi_child.kpi_child_two_name} </td>
                                                     <td rowspan=""> ${kpi_child.kpi_child_baseline} </td>`;
                                    if (kpi_child.plans && Array.isArray(kpi_child
                                            .plans)) {
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
