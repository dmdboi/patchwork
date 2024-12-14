@extends('main::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('main.name') !!}</p>
@endsection
