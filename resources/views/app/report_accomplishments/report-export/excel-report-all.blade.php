@php
    $first = 1;
@endphp
@php
    $kpi_repeat[0] = null;
    $c = 1;
    $objective_array = [];
@endphp
@forelse($planAccomplishments as $planAcc)
    @php
        $offices = $planAcc->getOfficeFromKpiAndOfficeList($only_child_array, $off_level);
    @endphp

    <h3 style="background-color: #e7e7ff; padding: 10px; border: 1px solid gray;">KPI
        {{ $planAcc->Kpi->KeyPeformanceIndicatorTs[0]->name }}</h3>

    @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
        @forelse($offices  as $office)

            @if(auth()->user()->is_admin || auth()->user()->hasRole('super-admin'))
                <h4 style="padding: 10px; border: 1px solid #000; border-bottom: none;">Office: {{ $office->officeTranslations[0]->name }}</h4>
            @endif

            @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                    @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                        @include('app.report_accomplishments.report-export.report-pdf-includes.view-kpi123')
                    @else
                        @include('app.report_accomplishments.report-export.report-pdf-includes.view-kpi12')
                    @endif
                @else
                    @include('app.report_accomplishments.report-export.report-pdf-includes.view-kpi1')
                @endif
            @else
                @include('app.report_accomplishments.report-export.report-pdf-includes.view-kpi')
            @endif
        @empty
        @endforelse

        @php
            $kpi_repeat[$c] = $planAcc->Kpi->id;
            $c++;
        @endphp
    @endif
@empty
@endforelse
