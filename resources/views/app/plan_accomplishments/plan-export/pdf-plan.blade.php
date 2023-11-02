<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        table {
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 5px;
        }

        body {
            max-width: 900px;
            margin: 10px auto;
            padding: 10px 50px;
        }

        .s1 {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .h3 {
            color: #057582;
            font-family: "Times New Roman", serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        .s5 {
            color: #057582;
            font-family: "Times New Roman", serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
            font-size: 16pt;
        }

        .s6 {
            color: #00f;
            font-family: "Times New Roman", serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
            font-size: 16pt;
        }

        p {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
            margin: 0pt;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }

        .fullname {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div>
        <div style="text-align: center; margin-top: 25px; margin-bottom: 30px;">
            <img src="{{ public_path('/images/logo.png') }}" width="80" height="100" alt="JU LOGO">
            <h2 style="padding-top: 5pt; text-align: center">Jimma University</h2>
            <h3 style="text-align: center">
                Strategic Management Senior Directorate
            </h3>
            <h4 style="text-align: center">Plan Document</h4>
        </div>
        <p style="text-indent: 0pt; text-align: left"><br /></p>
        <table style="border-collapse: collapse;" cellspacing="0">
            <tr style="height: 15pt">
                <td style="width: 325pt">
                    <p class="s1" style="
              text-align: left;
            ">
                        Name of Institution: <u class="fullname">Jimma University</u>
                    </p>
                </td>
                <td style="width: 107pt">
                    <p class="s1" style="
              text-align: left;
            ">
                        Year: <u>{{ $planning_year[0]->planingYearTranslations[0]->name ?? '-' }}</u>
                    </p>
                </td>
            </tr>
            <tr style="height: 15pt">
                {{-- <td style="width: 325pt">
                    <p style="
              text-align: left;
            ">
                        College/Institute: <u>college name</u>
                    </p>
                </td> --}}

                <td style="width: 107pt">
                    <p class="s1" style="
              text-align: left;
            ">
                        Manager:
                        <u>{{ $managerName }}</u>
                    </p>
                </td>
            </tr>
            <tr style="height: 15pt">
                <td style="width: 325pt">
                    <p class="s1" style="
              text-align: left;
            ">
                        Office: <u>{{ $officeSentToBlade }}</u>
                    </p>
                </td>
                <td style="width: 107pt">
                    <p style="text-indent: 0pt; text-align: left"><br /></p>
                </td>
                <td style="width: 77pt">
                    <p style="text-indent: 0pt; text-align: left"><br /></p>
                </td>
            </tr>
        </table>
        <p style="text-indent: 0pt; text-align: left"><br /></p>


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

            <h3 style="background-color: #e7e7ff; padding: 10px; border: 1px solid #000;">KPI:
                {{ $planAcc->Kpi->KeyPeformanceIndicatorTs[0]->name }}</h3>

            @if (!in_array($planAcc->Kpi->id, $kpi_repeat))
                @forelse($offices  as $office)
                    @if (!$planAcc->Kpi->kpiChildOnes->isEmpty())
                        @if (!$planAcc->Kpi->kpiChildTwos->isEmpty())
                            @if (!$planAcc->Kpi->kpiChildThrees->isEmpty())
                                @include('app.plan_accomplishments.plan-export.plan-pdf-includes.view-kpi123')
                            @else
                                @include('app.plan_accomplishments.plan-export.plan-pdf-includes.view-kpi12')
                            @endif
                        @else
                            @include('app.plan_accomplishments.plan-export.plan-pdf-includes.view-kpi1')
                        @endif
                    @else
                        @include('app.plan_accomplishments.plan-export.plan-pdf-includes.view-kpi')
                    @endif
                @empty
                    <h4>No offices!</h4>
                @endforelse

                @php
                    $kpi_repeat[$c] = $planAcc->Kpi->id;
                    $c++;
                @endphp
            @endif
        @empty
        @endforelse
    </div>
</body>

</html>
