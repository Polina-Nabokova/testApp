@extends('main')

@section('title-page')Home @endsection

@section('content')
    @include('header')
    <div class="px-4 py-5 my-5 text-center">
        <h1 class="display-5 fw-bold text-body-emphasis">PHP Developer</h1>
        <h2>Test Assignment</h2>
        @include('user_generate')
    </div>
@endsection
