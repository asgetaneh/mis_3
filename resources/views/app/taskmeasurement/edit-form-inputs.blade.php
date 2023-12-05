@php $editing = isset($task) @endphp

<div class="row">

     @foreach($languages as $key => $lang)
     <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="{{'name_'.$lang->locale}}"
            label="{{'Name in '.$lang->name}}"
             maxlength="255"
            placeholder="{{'name in '.$lang->name}}"
            value="{{ $task_measurement->name ?? '' }}"
            required
        ></x-inputs.text>
    </x-inputs.group>
    <x-inputs.group class="col-sm-12">
        <x-inputs.textarea
            name="{{'description_'.$lang->locale}}"
            label="{{'Description in '.$lang->name}}"
            class="form-control summernote"
            maxlength=""
            placeholder="Description"
            >
            {{ $task_measurement->description ?? '' }}
        </x-inputs.textarea>
    </x-inputs.group>
 @endforeach
 

     <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150
            });
            $('.dropdown-toggle').dropdown()
        });
    </script>
</div>
