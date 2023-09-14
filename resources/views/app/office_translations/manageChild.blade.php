<ul>

    @forelse ($childs as $office)
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
            @endif

        </li>
    @empty
    @endforelse

</ul>
