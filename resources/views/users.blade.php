@extends('main')

@section('title-page')Users @endsection
@section('content')
@include('header')

<div class="container">
    @if($users->total())
        <div class="mt-1 mb-3 ">
            <span class="h3 align-top">All users</span>
            <span class="align-bottom"><a href="{{ route('users-create-form') }}" class="btn btn-success w-auto">Add new</a></span>
        </div>
        <table id="user-list-table">
            <thead>
                <tr>
                    <th class="even-grid-col text-center">№</th>
                    <th class="even-grid-col">Name</th>
                    <th class="even-grid-col">Email</th>
                    <th class="even-grid-col">Phone</th>
                    <th class="even-grid-col">Position</th>
                    <th class="even-grid-col text-center">Photo</th>
                </tr> 
            </thead>
            <tbody>               
                @foreach($users as $key => $user)
                    @include('includes.users', ['index' => ($count * ($users->currentPage() - 1)) + $key + 1])
                @endforeach
            </tbody>
        </table>
    <span class="show-pages-count">Showing <span>{{$shown}}</span> of {{$users->total()}}</span>
    <div class="container mt-3 mb-5 text-center">
        @if($users->hasMorePages())
            <button class="btn btn-primary load-more" data-count="{{ $count}}" data-page="{{ $users->currentPage() +1}}">Show more</button>
            <img src="/images/loading.gif" class="preloader-img"> 
        @endif
    </div>
<!--    <div class="container mt-3 pagination-wrap">
        
         {{ $users->links()}}
    </div>     -->
   
    @else
         <div class="row mt-1">
            
            <div class="col-lg-5">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="{{ route('users-create-form') }}" class="btn btn-success btn-lg">Add one</a>
                </div>
            </div>
            <div class="col-lg-1"> <p class="p-2">OR</p></div>
           
            @include('user_generate')            
        </div>
    @endif

</div>   
    <script type="module">
        $(document).ready(function() {
            $(document).on('click', '.load-more', function(e) {
                e.preventDefault();
                var but = $(this);
                but.next('.preloader-img').css('display','inline-block');
                var count = $(this).attr('data-count');
                var page = $(this).attr('data-page');

                $.ajax({
                    type: 'get',
                    url: "{{ route('load-users') }}",
                    data: { 'count': count, 'page': page },
                    success:function(response) {
                        $('#user-list-table').append(response.html);
                        but.next('.preloader-img').css('display','none');
                        $('.show-pages-count span').text(response.shown);
                        if(page < response.lastPage) {
                            $('.load-more').attr('data-page', parseInt(page) + 1);
                        } else {
                            $('.load-more').remove();
                        }
                        // lazyBox();
                    }
                });
            });
        })
    </script>

@endsection
 
