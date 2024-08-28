

<header class="d-flex justify-content-center py-3">
    <ul class="nav nav-pills">

        <li class="nav-item"><a href="/" class="@if(!strpos(url()->current(), '/api') && !strpos(url()->current(), '/users')) active @endif nav-link " aria-current="page">Home</a></li>
        <li class="nav-item"><a href="{{route('api')}}" class="nav-link @if(strpos(url()->current(), '/api')) active @endif ">Api documentation</a></li>
        <li class="nav-item"><a href="{{route('users')}}" class="nav-link @if(strpos(url()->current(), '/users')) active @endif ">Users</a></li>
    </ul>
</header>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

