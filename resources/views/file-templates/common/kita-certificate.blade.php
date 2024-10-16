@php
    $kitaName = $data['kita_name'];
    $date = $data['date'];
@endphp

<div class="kita-certificate">
    <p>{{ $kitaName }}</p>
    <p>{{ $date }}</p>
</div>
