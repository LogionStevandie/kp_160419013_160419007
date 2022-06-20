@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Ubah Role
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Role</a></li>
<li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('role.update',[$role->id])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Role</label>
                <input required type="text" name="name" maxlength="255" class="form-control" 
                value="{{old('name',$role->name)}}">
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="deskripsi" maxlength="255" class="form-control" 
                value="{{old('deskripsi',$role->deskripsi)}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
