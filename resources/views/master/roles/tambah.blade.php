@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Role
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Role</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('role.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Role</label>
                <input required type="text" name="name" maxlength="255" class="form-control" 
                value="{{old('name','')}}">
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="deskripsi" maxlength="255" class="form-control" 
                value="{{old('deskripsi','')}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>

@endsection
