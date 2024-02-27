@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card ">

            <div class="card-header">
                <h3 class="card-title">
                    <u>{{ $role->name }}</u> role
                </h3>

                {{-- <div class="card-tools">
                    <a href="/usergroup/" class="btn btn-primary btn-sm">back</a>

                </div> --}}
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <form method="POST" action="{{ route('roles.permissions.update') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input hidden="" value="{{ $role->id }}" name="role_id">
                                <select class="duallistbox" multiple="" name="permission[]" style="display: none;">

                                    @foreach ($role->permissions()->get() as $permission)
                                            <option value="{{ $permission->id }}" title="{{ $permission->name }}" selected>{{ $permission->name }}</option>
                                        @endforeach
                                        @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}" title="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

    {{-- <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>

    <script>
        $(function () {
            $('.duallistbox').bootstrapDualListbox({
                nonSelectedListLabel: 'Denied Privileges',
                selectedListLabel: 'Granted Privilages',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                moveSelectedLabel: "Grant Selected Privileges",
                moveAllLabel: "Grant All Privilages ",
                removeSelectedLabel: "Revoke Selected Privileges",
                removeAllLabel: "Revoke All privileges",
                selectorMinimalHeight: "400"
            });

            $('.duallistbox').on('change', function(){
                $('.duallistbox').bootstrapDualListbox('refresh', true);
            })
        })
    </script>
@endpush
