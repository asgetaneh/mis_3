@php $editing = isset($objective) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="goal_id" label="Goal" required>
            @php $selected = old('goal_id', ($editing ? $objective->goal_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Goal</option>

            @foreach($goals as $value => $label)
             @if(app()->getLocale() == $label->locale)
            <option value="{{ $label->translation_id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
             @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="perspective_id" label="Perspective" required>
            @php $selected = old('perspective_id', ($editing ? $objective->perspective_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Perspective</option>
            @foreach($perspectives as $value => $label)
            @if(app()->getLocale() ==$label->locale)
            <option value="{{ $label->translation_id }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label->name }}</option>
            @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

     @foreach($languages as $key => $lang)
      
            
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'output'.$lang->locale}}"
            label="{{'Output in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'output in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'outcome'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'outcome in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength="255"
            required>
              </x-inputs.textarea
        >
    </x-inputs.group>
 @endforeach

    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $objective->weight : ''))"
            max="255"
            step="0.01"
            placeholder="Weight"
            required
        ></x-inputs.number>
    </x-inputs.group>
</div>
