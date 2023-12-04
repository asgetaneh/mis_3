@extends('layouts.app')
@section('title', 'Task Index')

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
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="card-title">{{"Task Measurements List"}}</h4>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>                                     
                            <tr>
                                <th>#</th>
                                <th class="text-left">
                                    Name
                                </th>
                                <th class="text-left">
                                    Description
                                </th>
                                <th class="text-left">
                                    expected value
                                </th>   
                                    <th class="text-left">
                                    Reported value
                                </th> 
                                    <th class="text-left">
                                    Actual value
                                </th>  
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            <x-form
                                method="POST"
                                action="{{ route('taskassign.store') }}"
                                class="mt-4">
                            @forelse($TaskAssigns as $TaskAssign)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>
                                        {{ $TaskAssign->task->name ?? '-' }}
                                    </td>
                                    <td>
                                        {!! html_entity_decode($TaskAssign->task->description ?? '-'  ) !!}
                                    </td>
                                    <td>
                                        {{ $TaskAssign->expected_value }}
                                    </td>
                                    <td>
                                        {{ $TaskAssign->expected_value }}
                                    </td>
                                    <td>
                                        <x-inputs.group class="col-sm-12">
                                            <x-inputs.number
                                                name="Expected_value"  :value="old('Expected_value', ($TaskAssign ? $TaskAssign->Expected_value : ''))"
                                                max="255"
                                                step="0.01"
                                                placeholder="Expected value"
                                                required
                                            ></x-inputs.number>
                                        </x-inputs.group>
                                    </td>
                                </tr>
                             @empty
                                <tr>
                                    <td colspan="7">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="6">
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="icon ion-md-save"></i>
                                        @lang('crud.common.create')
                                    </button>
                                </td>
                            </tr>
                            </x-form>
                    </tbody>
                </table>
                {{-- <div class="float-right">
                    {!! $objective->objectiveTranslations[0]->render() !!}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
