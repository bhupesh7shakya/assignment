@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="height:100vh">
    <x-card.card >
        {{-- <center>
            <img class="img-thumbnail border-0"  width="300" src="{{asset('images/undraw_secure_login_pdn4.png')}}" alt="" srcset="">
        </center> --}}
        <form method="post" action="{{route('user.register')}}" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">First Name</label>
              <input type="text" name="first_name" value="{{old('first_name')}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              @error('first_name')
            <span class="text-danger">{{$message}}</span>
              @enderror
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">last Name</label>
              <input type="text" name="last_name" value="{{old('last_name')}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              @error('last_name')
                <span class="text-danger">{{$message}}</span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email address</label>
              <input type="email" name="email" value="{{old('email')}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              @error('email')
                <span class="text-danger">{{$message}}</span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password"  name="password" class="form-control" id="exampleInputPassword1">
              @error('password')
              <span class="text-danger">{{$message}}</span>
            @enderror
            </div>
            {{-- <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
              <input type="password"  name="c_password" class="form-control" id="exampleInputPassword1">
              @error('password')
              <span class="text-danger">{{$message}}</span>
               @enderror
            </div> --}}
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Gender</label>
              <input type="text"  name="gender" class="form-control" id="gender">
              @error('gender')
              <span class="text-danger">{{$message}}</span>
               @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Address</label>
                <input type="text"  name="address" class="form-control" id="address">
                @error('address')
                <span class="text-danger">{{$message}}</span>
                 @enderror
            </div>
            <div class="mb-3">
                <label for="DOB" class="form-label">DOB</label>
                <input type="date"  name="dob" class="form-control" id="dob">
                @error('dob')
                <span class="text-danger">{{$message}}</span>
                 @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text"  name="phone" class="form-control" id="phone">
                @error('phone')
                <span class="text-danger">{{$message}}</span>
                 @enderror
            </div>



            {{-- <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div> --}}
            <button type="submit" class="btn  float-end btn-outline-info">
                <label style="white-space:nowrap;">Register</label>
                {{-- <span class="material-icons">
                login
                </span></button> --}}
          </form>
        </x-card>
        @if (session('msg'))
          <p class="bg-danger p-3">{{session('msg')}}</p>
        @endif
</div>

@endsection
