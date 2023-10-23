@extends('header')
@section('title','Login')
@section('active2','active')
@section('constant')
<div class="container">
  <div class="row" style="margin-top:6%;">
    <div class="col-sm-4"></div>
    <div class="col-sm-4" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
    <div style="padding-top: 15px;text-align:center;"><h3>Login</h3></div>
    @if(session()->has('message'))<h6> {{ session()->get('message') }}</h6>@endif
  <form action="{{ route('submit.login') }}" class="needs-validation" name="login-form" novalidate method="post">
    @csrf
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email" required>
      <!-- <div class="invalid-feedback">Please fill out this field.</div> -->
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" required>
      <!-- <div class="invalid-feedback">Please fill out this field.</div> -->
    </div>
    <div class="form-group form-check row">
    <div class="col-sm-6">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember" required> remember me

      </label>
     </div>
<div class="col-sm-6" style="text-align: end;">
<a href="">Forgot password?</a>
</div>
    </div>
    <div class="form-group" style="text-align:center;">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
<div class="form-group" style="margin-bottom:25px; text-align:center;">
<p>Not a member? <a href="{{ route('user.register') }}">Register</a></p>
</div>

  </form>
</div>
</div>
</div>
<script>
  $(function() {
    $("form[name='login-form']").validate({
     rules: {
      // firstname: "required",
      // lastname: "required",
      remember :
      {
        required: false,
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 5
      }
    },

    messages: {
      // firstname: "Please enter your firstname",
      // lastname: "Please enter your lastname",
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      email:{
        required: "Please provide a email address",
        email : "Please enter a valid email address"
      },
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>
@endsection


