<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if ($slot->isNotEmpty())
{{ $slot }}
@else
{{--<img src="{{  }}" class="logo" alt=""/>--}}
@endif
</a>
</td>
</tr>
