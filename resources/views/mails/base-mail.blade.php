@component('mail::message')

{{-- Subject --}}
@if (! empty($subject))
# {{ $subject }}
@endif

{{-- Greeting --}}
@if (! empty($greeting))
## {{ $greeting }}
@endif

{{-- Intro Lines --}}
@isset($introLines)
@foreach ($introLines as $line)
@markdown($line)

@endforeach
@endisset

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
@isset($outroLines)
@foreach ($outroLines as $line)
@markdown($line)

@endforeach
@endisset

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
