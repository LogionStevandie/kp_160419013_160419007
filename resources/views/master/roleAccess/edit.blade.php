@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Role
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('roleAccess.index')}}">Role</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('roleAccess.update', [$roleAccess->idRole])}}" method="POST" >
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Nama Role</label>
            <input disabled required type="text" name="nama" class="form-control" 
            value="{{old('nama',$roleAccess->nama)}}" >
        </div>

        <div class="form-group">
            
            @foreach($dataMenu as $data)      
                <input type="checkbox" id="menuIni{{$data->idMenu}}" class="form-check-input" name= "menu[]" value="{{$data->idMenu}}"{{'$data->idMenu' == old('idMenu',$data->idMenu)? 'checked' :'' }}> 
                <label for="title">{{$data->nama}}</label><br>
            @endforeach
        </div>
        <br>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
