@component('mail::message')

{{-- Subject --}}
# {{ __('notifications.database_backup.subject') }}

{{-- Greeting --}}
## {{ __('notifications.database_backup.greeting') }}

{{-- Intro Lines --}}
{{ __('notifications.database_backup.first_line') }}

{{-- Salutation --}}
@if (! empty($salutation))
{!! $salutation !!}
@else
@lang(
'notifications.common.salutation',
[
'from' => __('notifications.common.salutation_from'),
]
)
@endif
@endcomponent
