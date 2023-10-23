@extends('header')
@section('title','History')
@section('constant')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        @if(session()->has('message'))
        <div class="alert alert-success">
  <strong>Success!</strong> {{ session()->get('message') }}
</div>
@endif
<div class="col-sm-12" style="border: 1px solid #ece4e4;box-shadow: 2px 11px 25px #ddd8d8;border-radius: 10px;">
 <div style="text-align: center;"><h2>History</h2></div>
    <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sl.No</th>
        <th>File Name</th>
        <th>File Type</th>
        <th>File Language</th>
        <th>File Duration</th>
        <th>File Size</th>
        <th>File Uploaded Date</th>
        <th colspan="2" style="text-align:center;">action</th>
      </tr>
    </thead>
    <tbody>

     @foreach($usersfiles as $usersfile)
             <tr>
             <th>{{ $loop->iteration }}</th>
            <th>{{ $usersfile->file_realname }}</th>
            <th>{{ $usersfile->file_type }}</th>
            <th>{{ $arrylanguage[$usersfile->file_lang] }}</th>
            <th>{{ $usersfile->file_duration }}</th>
            <th>{{ $usersfile->file_size }}</th>
            <th>{{ $usersfile->created_at }}</th>
            <th><a href="{{ route('user.edit.history',encrypt($usersfile->id)) }}" class="btn btn-info">Edit</a></th>
            <th><a href="{{ route('user.delete.history',encrypt($usersfile->id)) }}" class="btn btn-danger">Delete</a></th>
        </tr>
           @endforeach

    </tbody>
  </table>
</div>
        </div>
    </div>
</div>
@endsection


