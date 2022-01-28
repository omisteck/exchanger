@component('mail::message')
# Thresholds Alert

This is the notify you that {{ $data->base_currency }} is now {{ $data->codition }} {{ $data->other_currency }} at {{ $data->value }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
