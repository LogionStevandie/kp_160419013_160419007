@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Menu
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('menu.index')}}">Menu</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('menu.update', [$menu->MenuID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Menu</label>
                <input required type="text" name="Name" maxlength="255" class="form-control" 
                value="{{old('Name',$menu->Name)}}">
            </div>
            <div class="form-group">
                <label for="title">Url Menu</label>
                <input required type="text" name="Url" maxlength="255" class="form-control" placeholder="Ex. menu.create, menu.show, menu.index, menu.destroy"
                value="{{old('Url',$menu->Url)}}" >
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="Deskripsi" maxlength="255" class="form-control"
                value="{{old('Deskripsi',$menu->Deskripsi)}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection
