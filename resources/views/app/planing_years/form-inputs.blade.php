@php $editing = isset($planingYear) @endphp

<div class="row">
    @foreach($languages as $key => $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'name_'.$lang->locale}}"
                label="{{'Name in '.$lang->name}}"
                maxlength="255"
                value="{{ $planingTranslations[$lang->locale][0]->name ?? '' }}"
                placeholder="{{'name in '.$lang->name}}"
                required
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea
                name="{{'description_'.$lang->locale}}"
                label="{{'Description in '.$lang->name}}"
                maxlength=""
                placeholder="Description"
                required>{{ $planingTranslations[$lang->locale][0]->description ?? '' }}
                </x-inputs.textarea
            >
        </x-inputs.group>
    @endforeach
</div>
