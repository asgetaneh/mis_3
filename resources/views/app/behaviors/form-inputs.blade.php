<div class="row">

    @foreach($languages as $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'name_'.$lang->locale}}"
                label="{{'Name in '.$lang->name}}"
                 maxlength="255"
                placeholder="{{'name in '.$lang->name}}"
                value="{{ $behaviorTranslations[$lang->locale][0]->name ?? '' }}"
                required
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea
                name="{{'description_'.$lang->locale}}"
                label="{{'Description in '.$lang->name}}"
                maxlength=""
                placeholder="{{'Description in '.$lang->name}}"
                required>{{ $behaviorTranslations[$lang->locale][0]->description ?? '' }}
                  </x-inputs.textarea
            >
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'slug_'.$lang->locale}}"
                label="{{'Slug in '.$lang->name}}"
                value="{{ $behaviorTranslations[$lang->locale][0]->slug ?? '' }}"
                placeholder="{{'Slug in '.$lang->name}}"
                required
            ></x-inputs.text>
        </x-inputs.group>

    @endforeach

</div>