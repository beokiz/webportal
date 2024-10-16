@extends('layouts.pdf-file')

@section('content')
    @include('file-templates.common.kita-certificate', compact('data'))
@endsection
