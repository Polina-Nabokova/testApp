@extends('main')

@section('title-page')User form @endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#photo').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image-preview').html('<img src="'+ e.target.result +'">');
                $('.select-btn').text('Selected, change');
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.choose-wrap').click(function (){
            $('#photo').trigger('click');
        });
    });

</script>
@section('content')
    <div class="container">
        <div class="row  mt-4">
            <div class="col-3"><a href="{{ route('users') }}" class="btn btn-primary">Back</a></div>
            <div class="col-9"><h2>Create new user</h2></div>
        </div>

        <div class="col-lg-6 mx-auto">
            <form action="{{ route('create-user') }}" method="post" enctype="multipart/form-data" >
                @csrf

                <div class="form-group">
                    <label for="name">Name
                        <span>*  @error('name') {{ $message }} @enderror</span>
                    </label>
                    <input type="text" name="name" placeholder="Name" id="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="email">Email  <span>* @error('email'){{ $message }} @enderror</span></label>
                    <input type="text" name="email" placeholder="Email" id="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="phone">Mobile Phone <span>* @error('phone') {{ $message }} @enderror</span></label>
                    <input type="text" name="phone" placeholder="+380112233444" id="phone" value="{{ old('phone') }}"
                           class="form-control @error('phone') is-invalid @enderror">
                </div>
                <div class="form-group">
                    <label for="position_id">Position <span>* @error('position_id') {{ $message }} @enderror</span></label>
                    <select name="position_id" id="position_id"  class="form-control @error('position_id') is-invalid @enderror">
                        <option value=""></option>
                        @foreach($positions as $position)
                            <option value="{{$position['id']}}" @selected(old('position_id') == $position)>{{$position['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="photo" class="label-text">User photo <span>* @error('photo') {{ $message }} @enderror</span></label>
                    <div class="input-field">
                        <input type="file" name="photo" id="photo" >
                        <div class="choose-wrap row">

                            <div class="col-2">
                                <div id="image-preview" ></div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 96C0 60.7 28.7 32 64 32l384 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zM323.8 202.5c-4.5-6.6-11.9-10.5-19.8-10.5s-15.4 3.9-19.8 10.5l-87 127.6L170.7 297c-4.6-5.7-11.5-9-18.7-9s-14.2 3.3-18.7 9l-64 80c-5.8 7.2-6.9 17.1-2.9 25.4s12.4 13.6 21.6 13.6l96 0 32 0 208 0c8.9 0 17.1-4.9 21.2-12.8s3.6-17.4-1.4-24.7l-120-176zM112 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/></svg>
                            </div>
                            <div class="select-btn col-4">Select File</div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Send</button>
            </form>
        </div>



    </div>

@endsection
