@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Bank
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('bank.index')}}">Bank</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
          
    <form action="{{route('bank.update',[$bank->id])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

                <div class="form-group">
                    <label for="title">Nama Bank</label>
                    <input require type="text" name="name" class="form-control" 
                    value="{{old('name',$bank->name)}}">
                </div>

                <div class="form-group">
                    <label for="title">Alias</label>
                    <input require type="text" name="alias" class="form-control" 
                    value="{{old('alias',$bank->alias)}}">
                </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection




