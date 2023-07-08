<div class="row">
     {{-- @foreach($languages as $key => $lang)


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
    </x-inputs.group>
 @endforeach --}}

    @foreach($languages as $lang)
        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'name_'.$lang->locale}}"
                label="{{'Name in '.$lang->name}}"
                 maxlength="255"
                placeholder="{{'name in '.$lang->name}}"
                value="{{ $goalTranslations[$lang->locale][0]->name ?? '' }}"
                required
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'out_put_'.$lang->locale}}"
                label="{{'Output in '.$lang->name}}"
                 maxlength=""
                placeholder="{{'output in '.$lang->name}}"
                value="{{ $goalTranslations[$lang->locale][0]->out_put ?? '' }}"
                required
            ></x-inputs.text>
        </x-inputs.group>

         <x-inputs.group class="col-sm-12">
            <x-inputs.text
                name="{{'out_come_'.$lang->locale}}"
                label="{{'Outcome in '.$lang->name}}"
                 maxlength=""
                placeholder="{{'outcome in '.$lang->name}}"
                value="{{ $goalTranslations[$lang->locale][0]->out_come ?? '' }}"
                required
            ></x-inputs.text>
        </x-inputs.group>

        <x-inputs.group class="col-sm-12">
            <x-inputs.textarea
                name="{{'description_'.$lang->locale}}"
                label="{{'Description in '.$lang->name}}"
                maxlength=""
                required>{{ $goalTranslations[$lang->locale][0]->description ?? '' }}
                  </x-inputs.textarea
            >
        </x-inputs.group>

    @endforeach

</div>
