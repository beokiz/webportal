@extends('layouts.pdf-file')

@section('content')
    @include('file-templates.common.screening', compact('data'))
@endsection
