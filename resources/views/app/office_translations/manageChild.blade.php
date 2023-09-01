<ul>

    @foreach ($childs as $office)
        <li>


            @if ($office->subordinate == true)
                &nbsp; &nbsp;
            @else
            @endif

            @if (count($office->offices) == 0)
                <i class="fa fa-minus"> </i>
            @else
                <i class="fa fa-plus"> </i>
            @endif

            <h5 style="display: inline;">{{ $office->officeTranslations[0]->name }}</h5>

            <br>

            @if (count($office->offices))
                @include('app.office_translations.manageChild', ['childs' => $office->offices])

                {{-- @foreach ($office->positions as $position)
                    @if (count($office->positions))
                        &nbsp;&nbsp;&nbsp;
                        <i class="la la-caret-right"> </i>
                        {{ $position->name }}-

                        @foreach ($position->positionCodes as $positionCode)
                            {{ $positionCode->code }}-[ {{ ucfirst($positionCode->employee->name ?? '-') }} ]




                            <br>
                        @endforeach
                    @endif
                @endforeach --}}
            @endif

        </li>
    @endforeach

</ul>
