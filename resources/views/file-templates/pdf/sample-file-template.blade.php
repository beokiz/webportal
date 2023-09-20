@extends('layouts.pdf-file')

@section('content')
    @include('file-templates.common.sample-file-template', compact('data'))
@endsection
