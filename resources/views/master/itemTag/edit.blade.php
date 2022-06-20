@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Tag Item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemTag.index')}}">Tag item</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
   <form action="{{route('itemTag.update',[$ItemTag->ItemTagID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Nama Item Tag</label>
                <input require type="text" name="Name" class="form-control" 
                value="{{old('Name',$ItemTag->Name)}}" maxlength="50">
            </div>

            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input require type="text" name="Desc" class="form-control" 
                value="{{old('Desc',$ItemTag->Desc)}}" maxlength="512">
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simoan</button>
        </div>
    </form>
</div>
@endsection

