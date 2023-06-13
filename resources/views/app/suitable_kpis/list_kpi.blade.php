@extends('layouts.app')

@section('content')
<div class="container">
    <div class="searchbar mt-0 mb-4">
        <div class="row">
            <div class="col-md-6">
                <form>
                    <div class="input-group">
                        <input
                            id="indexSearch"
                            type="text"
                            name="search"
                            placeholder="{{ __('crud.common.search') }}"
                            value="{{ $search ?? '' }}"
                            class="form-control"
                            autocomplete="off"
                        />
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon ion-md-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right">
                @can('create', App\Models\KeyPeformanceIndicator::class)
                 <div class="search-area col-md-12">
                    <div></div>


                    <div class="pull-right">
                        <div class="widget-toolbar no-border">


                            <button class="btn btn-sm btn-round btn-bold btn-white bigger btn-default dropdown-toggle" data-toggle="dropdown">
                                <b class="text-primary">Action</b>
                                <i class="ace-icon fa fa-chevron-down icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-yellow dropdown-menu-right dropdown-caret">
                                <li>
                                    <a href="#" id="suitablebutton2">
                                        <i class="ace-icon fa fa-stop green"></i>
                                        Select As Suitable</a>
                                </li>
                                <li>
                                    <a href="#" id="nonsuitablebutton">
                                        <i class="ace-icon fa fa-stop red"></i>
                                        Select As Non Suitable</a>
                                </li>


                            </ul>
                        </div>

                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    @lang('crud.key_peformance_indicators.index_title')
                </h4>
                
            </div>
            
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                        <th class="text-left">
                                <label class="inline">
                                    <input type="checkbox" name="initiative[]" id="checkall" value="" class="ace">
                                    <span class="lbl"></span>
                                </label>
                            </th>
                            <th class="text-left">
                                Name
                            </th>
                            <th class="text-left">
                                Description
                            </th> 
                             
                            <th class="text-left">
                                @lang('crud.key_peformance_indicators.inputs.weight')
                            </th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <form action="{{route('select-suitable-kpi')}}" id="initiative_form2" method="get">
                        <input type="hidden" name="planyear" value="{{$planyear->id}}">
                        <input type="hidden" name="office" value="{{$m_office->id}}">
                        <input type="hidden" name="nonsuitable" id="nonsuit" value="">
                        @forelse($kpis as $kpi)
                        
                        <tr>
                          <td>
                                <label class="inline">
                                <input type="checkbox" name="kpi[]" value="{{$kpi->id}}" class="ace" required>
                                <span class="lbl"></span>
                            </label>
                            </td>
                            <td>
                                {{
                                $kpi->keyPeformanceIndicatorTs[0]->name ?? '-' }}
                            </td>
                            <td>
                                {{
                                $kpi->keyPeformanceIndicatorTs[0]->description ?? '-' }}
                            </td>
                            
                            <td>
                                {{ $kpi->weight ?? '-' }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                @forelse ($kpi->suitableKpis as $kpi_exist_on_suitable)

                                    <p class="bg-success text-white ">Suitable</p>

                                @empty

                                    <p class="bg-danger text-white ">Not Suitable</p>

                                @endforelse
                            </td>
                        </tr>
                         @empty
                        <tr>
                            <td colspan="6">
                                @lang('crud.common.no_items_found')
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    </form>
                    <tfoot>
                        <tr>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
		$(function () {


$('#checkall').click(function () {
var $chk = $(':checkbox');
$chk.prop('checked', $(this).is(':checked'));

})
$('#suitablebutton2').click(function () {
$('#initiative_form2').submit();
})
$('#nonsuitablebutton').click(function () {
$('#nonsuit').val(true);
$('#initiative_form2').submit();
})
});
	</script>

@endsection
