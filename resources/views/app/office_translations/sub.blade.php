<div class="table-responsive">
    <table class="table table-bordered table-hover mt-3">
        <thead>
            <tr>
                <th class="text-left">
                   Sub-office
                </th>
                <th class="text-left">
                                #
                </th>
                <th class="text-left">
                    @lang('crud.office_translations.inputs.name')
                </th>
                <th class="text-left">
                    @lang('crud.office_translations.inputs.description')
                </th>
                <th class="text-left">
                    @lang('crud.office_translations.inputs.translation_id')
                </th>
                <th>
                    Manager
                </th>
                <th class="text-center">
                    @lang('crud.common.actions')
                </th>
            </tr>
        </thead>
        <tbody>
            @php $count =0;@endphp
            @forelse($office->officeTranslations as $officeTranslation)
                @if (app()->getLocale() == $officeTranslation->locale)
                    @php $office = $officeTranslation->office; @endphp
                    @php $count = $count+1;@endphp
                    <tr>
                        <td>
                            <p>
                                <a class="btn btn-info btn-flat" data-toggle="collapse" href="#off{{ $officeTranslation->translation_id }}"
                                    role="button" aria-expanded="false" aria-controls="collapseExample0">
                                    >>
                                </a>
                            </p>
                        <td>

                            {{ $count }}
                        </td>
                        <td>{{ $officeTranslation->name ?? '-' }}</td>
                        <td>
                            {{ $officeTranslation->description ?? '-' }}
                        </td>
                        <td>
                            {{ $officeTranslation->office->office->officeTranslations[0]->name ?? '-' }}
                        </td>

                        <td>
                            {!! $officeTranslation->office->users[0]->name ?? '<span class="badge badge-secondary">Not assigned</span>' !!}
                            @if ($officeTranslation->office->users->count() > 0)
                                <form
                                    action="{{ route('office-manager.remove', $officeTranslation->office->users[0]->id) }}"
                                    class="d-inline" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger ml-3 float-right" title="Remove manager from Office">Remove</button>
                                </form>
                            @endif
                        </td>

                        <td class="text-center" style="width: 134px;">
                            <div role="group" aria-label="Row Actions" class="btn-group">
                                @can('update', $officeTranslation)
                                    <a href="{{ route('office-translations.edit', $officeTranslation) }}">
                                        <button type="button" class="btn btn-light">
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    @endcan @can('view', $officeTranslation)
                                    <a href="{{ route('office-translations.show', $officeTranslation) }}">
                                        <button type="button" class="btn btn-light">
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    @endcan
                                    @can('delete', $officeTranslation)
                                        <form action="{{ route('office-translations.destroy', $officeTranslation) }}"
                                            method="POST" onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light text-danger">
                                                <i class="icon ion-md-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="4">
                        @lang('crud.common.no_items_found')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
