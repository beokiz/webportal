@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
@markdown($line)

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
case 'success':
case 'error':
$color = $level;
break;
default:
$color = 'primary';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
@markdown($line)

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang(
'notifications.common.salutation',
[
'from' => __('notifications.common.salutation_from'),
]
)
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
'notifications.common.subcopy',
[
'actionText' => $actionText,
]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
