@extends('main')

@section('title-page')Users @endsection
@section('content')
@include('header')

<div class="container">
    @if($users)
        <div class="row">
            <div class="col-8"><h2 class="mt-4">All users</h2></div>
            <div class="col-4 text-right">
                <a href="{{ route('create-form') }}" class="btn btn-success mt-4">Add new</a>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-2 even-grid-col">Name</div>
            <div class="col-2 even-grid-col">Phone</div>
            <div class="col-2 even-grid-col">Email</div>
            <div class="col-2 even-grid-col">Position</div>
            <div class="col-2 even-grid-col">Photo</div>
            <div class="col-2 even-grid-col">Action</div>
        </div>
    @foreach($users as $user)
            <div class="row  text-center">
                <div class="col-2 odd-grid-col">{{ $user->name }}</div>
                <div class="col-2 odd-grid-col">{{ $user->phone }}</div>
                <div class="col-2 odd-grid-col">{{ $user->email }}</div>
                <div class="col-2 odd-grid-col">{{ $user->position }}</div>
                <div class="col-2 odd-grid-col">
                    <img src="{{ asset($user->photo) }}" >
                </div>
                <div class="col-2 odd-grid-col">Edit/Delete</div>
            </div>
    @endforeach
    {{ $users->links()}}
    @else
        <div class="row">
            @include('user_generate')
            <div class="col-lg-6">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="{{ route('create-form') }}" class="btn btn-success mt-4 btn-lg">Add one</a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

