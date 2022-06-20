@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Gudang Area Simpan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mGudangAreaSimpan.index')}}">Gudang-Area-Simpan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mGudangAreaSimpan.update', [$mGudangAreaSimpan->MGudangAreaSimpanID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama</label>
                <input required type="text" name="name" maxlength="50" class="form-control" 
                value="{{old('cname',$mGudangAreaSimpan->cname)}}">
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection
