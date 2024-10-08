@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="height:100vh">
    <x-card.card title="AMS Login">
        <center>
            <img class="img-thumbnail border-0"  width="300" src="{{asset('images/undraw_secure_login_pdn4.png')}}" alt="" srcset="">
        </center>
        <form method="post" action="
        {{-- {{route('admin.authentication')}} --}}
        " class="needs-validation" novalidate>
            @csrf
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
            {{-- <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div> --}}
            <button type="submit" class="btn  float-end btn-outline-info">
                <label style="white-space:nowrap;">Login</label>
            </button>

            {{-- <span class="material-icons">
                login
            </span></button> --}}
        </form>
        <br>

            <a  href="{{route("user.register")}}">Register Now</a>
            </x-card>
        @if (session('msg'))
          <p class="bg-danger p-3">{{session('msg')}}</p>
        @endif
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<x-script.alert></x-script.alert>
@endsection
