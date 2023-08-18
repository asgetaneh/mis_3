@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="searchbar mt-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <form>
                        <div class="input-group">
                            <input id="indexSearch" type="text" name="search" placeholder="{{ __('crud.common.search') }}"
                                value="{{ $search ?? '' }}" class="form-control" autocomplete="off" />
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon ion-md-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    @can('create', App\Models\OfficeTranslation::class)
                        <a href="{{ route('office-translations.create') }}" class="btn btn-primary">
                            <i class="icon ion-md-add"></i> @lang('crud.common.create')
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">
                        @lang('crud.offices.index_title')
                    </h4>
                </div>
                <div class="table-responsive">
                    @forelse($officesT  as $officeTranslation)
                        @if (app()->getLocale() == $officeTranslation->locale)
                            <ul>
                                <ol>
                                    {{ $officeTranslation->name ?? '-' }}
                                    @php
                                        $child_ones = $officeTranslation->office->offices;
                                    @endphp
                                    @forelse($child_ones  as $child_one)
                                        <ul>
                                            <ol>
                                                {{ $child_one->officeTranslations[0]->name ?? '-' }}
                                                @php
                                                    $child_twos = $child_one->offices;
                                                @endphp
                                                @forelse($child_twos  as $child_two)
                                                    <ul>
                                                        <ol>
                                                            {{ $child_two->officeTranslations[0]->name ?? '-' }}
                                                            @php
                                                                $child_threes = $child_two->offices;
                                                            @endphp
                                                             @forelse($child_threes  as $child_three)
                                                    <ul>
                                                        <ol>
                                                            {{ $child_three->officeTranslations[0]->name ?? '-' }}
                                                            @php
                                                                $child_fours = $child_three->offices;
                                                            @endphp
                                                        </ol>
                                                    </ul>
                                                @empty
                                                @endforelse
                                                        </ol>
                                                    </ul>
                                                @empty
                                                @endforelse
                                            </ol>
                                        </ul>
                                    @empty
                                    @endforelse
                                </ol>
                            </ul>
                        @endif
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
