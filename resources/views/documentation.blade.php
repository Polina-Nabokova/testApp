@extends('main')

@section('title-page')Documentation @endsection

@section('content')
    @include('header')
    <div class="container">
         <span class="h3 my-1">Api documentation for backend test assignment</span>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-4">
                <span class="opblock opblock-post">POST</span>
                <b>/users</b>
                Register new user
            </div>
            <div class="col-6"><input type="text" value="{{ route('create') }}" class="form-control" readonly="true"></div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <span class="opblock opblock-get">GET</span>
                <b>/users</b>
                Returns a list of users.
            </div>
            <div class="col-6"><input type="text" value="{{ route('users') }}" class="form-control" readonly="true"></div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
               <span class="opblock opblock-get">GET</span>
               <b>/users/{id}</b>
               Returns a user by id
            </div>
            <div class="col-6"><input type="text" value="{{ route('user', 1) }}" class="form-control" readonly="true"></div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <span class="opblock opblock-get">GET</span>
                <b>/positions</b>
                Get users positions
            </div>
            <div class="col-6"><input type="text" value="{{ route('positions') }}" class="form-control" readonly="true"></div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                <span class="opblock opblock-get">GET</span>
                <b>/token</b>
                Get a new token
            </div>
            <div class="col-6"><input type="text" value="{{ route('token') }}" class="form-control" readonly="true"></div>
        </div>
       
    </div>
@endsection
