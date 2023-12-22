@php $editing = isset($task) @endphp

<div class="row">

    <x-inputs.group class="col-sm-12">

        <input type="hidden" value="{{ $task->id }}" name="taskId">

        <x-inputs.group class="col">
            <x-inputs.select required class="select2" multiple="multiple" name="performers[]"
                data-placeholder="Select Performers for the Task" label="Performers List">
                @forelse($performersNotAssigned as $key => $performer)
                    <option value="{{ $performer->user_id }}">{{ $performer->user->name }}</option>
                @empty
                    <option value="" disabled>No Performer!</option>
                @endforelse
            </x-inputs.select>
        </x-inputs.group>

        <div class="w-100 row pl-2">
            <x-inputs.group class="col">

                <x-inputs.date label="Start Date" autocomplete="off" class="form-control col"
                    data-inputmask-alias="dd/mm/yyyy"
                    data-inputmask="'yearrange': { 'minyear': '{{ \Andegna\DateTimeFactory::now()->getYear() - 1 }}'}"
                    data-val="true" data-val-required="Required" id="start_date" name="start_date"
                    placeholder="date/month/year" type="text" spellcheck="false" required pattern="[0-9\/]*"
                    oninvalid="setCustomValidity('Please enter a valid date!')"
                    onchange="try{setCustomValidity('')}catch(e){}">
                </x-inputs.date>
            </x-inputs.group>

            <x-inputs.group class="col">

                <x-inputs.date label="End Date" autocomplete="off" class="form-control col"
                    data-inputmask-alias="dd/mm/yyyy"
                    data-inputmask="'yearrange': { 'minyear': '{{ \Andegna\DateTimeFactory::now()->getYear() - 1 }}'}"
                    data-val="true" data-val-required="Required" id="end_date" name="end_date"
                    placeholder="date/month/year" type="text" spellcheck="false" required pattern="[0-9\/]*"
                    oninvalid="setCustomValidity('Please enter a valid date!')"
                    onchange="try{setCustomValidity('')}catch(e){}">
                </x-inputs.date>
            </x-inputs.group>
        </div>

        <div class="w-100 row pl-2">
            <x-inputs.group class="col">
                <x-inputs.number name="expectedValue" label="Expected Value"
                    value="" placeholder="expected value"
                    required></x-inputs.number>
            </x-inputs.group>

            <x-inputs.group class="col">
                <x-inputs.number name="timeGap" label="Time Gap"
                    value="" placeholder="time gap"
                    ></x-inputs.number>
            </x-inputs.group>
        </div>
    </x-inputs.group>

</div>
