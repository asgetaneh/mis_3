@php $editing = isset($user) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $user->name : ''))"
            maxlength="255"
            placeholder="Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="email"
            label="Email"
            :value="old('email', ($editing ? $user->email : ''))"
            maxlength="255"
            placeholder="Email"
            required
        ></x-inputs.text>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="username"
            label="Username"
            :value="old('username', ($editing ? $user->username : ''))"
            maxlength="255"
            placeholder="Username"
            required
        ></x-inputs.text>
    </x-inputs.group> --}}

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.password
            name="password"
            label="Password"
            maxlength="255"
            placeholder="Password"
            :required="!$editing"
        ></x-inputs.password>
    </x-inputs.group> --}}

    <div class="form-group col-sm-12">
        {{-- <h4>Assign @lang('crud.roles.name')</h4>

        @foreach ($roles as $role)
        <div>
            <x-inputs.checkbox
                id="role{{ $role->id }}"
                name="roles[]"
                label="{{ ucfirst($role->name) }}"
                value="{{ $role->id }}"
                :checked="isset($user) ? $user->hasRole($role) : false"
                :add-hidden-value="false"
            ></x-inputs.checkbox>
        </div>
        @endforeach --}}

        <x-inputs.select
        name="roles[]"
        label="Role"
        multiple="multiple"
        class="form-control select2"
        required
    >
    <option value="" disabled>Select role</option>
    @foreach ($roles as $role)
            <option @if(in_array($role->id, $user->roles->pluck('id')->toArray())) selected @endif {{-- isset($user)?'selected':'' --}} value="{{ $role->id }}">{{ $role->name }}</option>
    @endforeach
</x-inputs.select>

    </div>
</div>
