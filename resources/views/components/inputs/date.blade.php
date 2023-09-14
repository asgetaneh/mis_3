@props([
    'name',
    'label',
    'value',
])

<x-inputs.basic :name="$name" label="{{ $label ?? ''}}" :value="$value ?? ''" :attributes="$attributes"></x-inputs.basic>
