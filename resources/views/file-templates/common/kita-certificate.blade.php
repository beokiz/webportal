@php
    $kitaName = $data['kita_name'];
    $date = $data['date'];
@endphp

<div class="kita-certificate" style="background-image: url({{ base64_encode_file_to_uri(resource_path('images/kita-certificate-template.jpg')) }});">
    <p class="kita-name">{{ $kitaName }}</p>
    <p class="date">{{ $date }}</p>
</div>
