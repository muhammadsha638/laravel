@extends('header')
@section('title','All Users')
@section('constant')
<div class="container">
  <h2>All Users</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sl.No</th>
        <th>Name</th>
        <th>Email</th>
        <th colspan="2" style="text-align:center;">action</th>
      </tr>
    </thead>
    <tbody>
     @foreach($users as $user)
             <tr>
             <th>{{ $loop->iteration }}</th>
            <th>{{ $user->name }}</th>
            <th>{{ $user->email }}</th>
            <!-- <th><img src="{{ asset('storage/images/'.$user->image) }}"></th> -->
            <th><a href="" class="btn btn-info">Edit</a></th>
            <th><a href="" class="btn btn-danger">Delete</a></th>
            
            
        </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection


