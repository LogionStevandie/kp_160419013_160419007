@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Menu
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('menu.index')}}">Menu</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('menu.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Menu</label>
                <input required type="text" name="Name" maxlength="255" class="form-control" 
                value="{{old('Name','')}}">
            </div>
            <div class="form-group">
                <label for="title">Url Menu</label>
                <input required type="text" name="Url" maxlength="255" class="form-control" placeholder="Ex. menu.create, menu.show, menu.index, menu.destroy"
                value="{{old('Url','')}}" >
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="Deskripsi" maxlength="255" class="form-control"
                value="{{old('Deskripsi','')}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

@endsection
