@extends('header')
@section('title','Register')
@section('active1','active')
@section('constant')
<div class="container">
  <div class="row" style="margin-top:;">
    <div class="col-sm-4"></div>
    <div class="col-sm-4" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
       @if(session()->has('message'))
        <div class="alert alert-success">
  <strong>Success!</strong> {{ session()->get('message') }}
</div>
@endif
    <div style="padding-top: 15px;text-align:center;"><h3>Registration</h3></div>
    <form action="{{ route('save.user') }}" class="needs-validation" novalidate method="post" enctype="multipart/form-data">
        @csrf
  <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter name" name="name" required>
      @error('name')<label class="error">{{ $message }}</label> @enderror
    </div>
    <div class="form-group" >
      <label for="email">Email</label>
      <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter Email" name="email" required>
      @error('email')<label class="error">{{ $message }}</label> @enderror
    </div>
    <div class="form-group">
      <label for="pwd">Password</label>
      <input type="password" class="form-control @error('pwd') is-invalid @enderror" id="pwd" placeholder="Enter password" name="pswd" required>
      @error('pwds')<label class="error">{{ $message }}</label> @enderror
    </div>
    <div class="form-group">
      <label for="cpwd">confirm password</label>
      <input type="password" class="form-control @error('cpwd') is-invalid @enderror" id="cpwd" placeholder="Enter re-password" name="cpswd" required>
      @error('cpwd')<label class="error">{{ $message }}</label> @enderror
    </div>
    <!-- <div class="form-group">
      <label for="file">Image</label>
      <input type="file" class="form-control" id="image" name="image" required>
    </div> -->
    <div class="form-group form-check">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember" checked required> I have read and agree to the terms
       </label>
     </div>
    <div class="form-group" style="text-align:center;">
    <button type="submit" class="btn btn-primary">Register</button>
</div>
<div class="form-group" style=" text-align:center;margin-bottom:25px;">
<p class="text-center">Have already an account? <a href="{{ route('user.login') }}">Login here</a></p>
</div>

  </form>
</div>
</div>
</div>
@endsection


