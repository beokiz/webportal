@extends('layouts.pdf-file')

@section('content')
    @include('file-templates.common.evaluation', compact('data'))
@endsection
