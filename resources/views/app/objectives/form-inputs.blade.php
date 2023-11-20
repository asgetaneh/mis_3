@php $editing = isset($objective) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="goal_id" label="Goal" class="form-control select2" required>
            @php $selected = old('goal_id', ($editing ? $objective->goal_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Goal</option>

            @foreach($goals as $value => $label)
             @if(app()->getLocale() == $label->locale)
            <option value="{{ $label->translation_id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
             @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="perspective_id" label="Perspective" class="form-control select2" required>
            @php $selected = old('perspective_id', ($editing ? $objective->perspective_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }} value="">Please select the Perspective</option>
            @foreach($perspectives as $value => $label)
            @if(app()->getLocale() ==$label->locale)
            <option value="{{ $label->translation_id }}" {{ $selected == $label->translation_id ? 'selected' : '' }} >{{ $label->name }}</option>
            @endif
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

     @foreach($languages as $key => $lang)

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $objectiveTranslations[$lang->locale][0]->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'out_put_'.$lang->locale}}"
            label="{{'Output in '.$lang->name}}"
             maxlength=""
            placeholder="{{'output in '.$lang->name}}"
            value="{{ $objectiveTranslations[$lang->locale][0]->out_put ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'out_put_'.$lang->locale}}"
            label="{{'Output in '.$lang->name}}"
            class="form-control summernote" 
            maxlength=""
            placeholder="{{'output in '.$lang->name}}"
            required>
            {{ $objectiveTranslations[$lang->locale][0]->out_put ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>

     {{-- <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'out_come_'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
             maxlength=""
            placeholder="{{'outcome in '.$lang->name}}"
            value="{{ $objectiveTranslations[$lang->locale][0]->out_come ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group> --}}

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'out_come_'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
            class="form-control summernote" 
            maxlength=""
            placeholder="{{'outcome in '.$lang->name}}"
            required>
            {{ $objectiveTranslations[$lang->locale][0]->out_come ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            class="form-control summernote" 
            maxlength=""
            placeholder="Description"
            required>
            {{ $objectiveTranslations[$lang->locale][0]->description ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>

    {{-- <x-inputs.group class="col-sm-12">
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
             maxlength=""
            placeholder="{{'output in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'outcome'.$lang->locale}}"
            label="{{'Outcome in '.$lang->name}}"
             maxlength=""
            placeholder="{{'outcome in '.$lang->name}}"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            maxlength=""
            required>
              </x-inputs.textarea
        >
    </x-inputs.group> --}}
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

     <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150
            });
            $('.dropdown-toggle').dropdown()
        });
    </script>
</div>
