@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Info Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('infoSupplier.index')}}">Info Supplier</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
   <form action="{{route('infoSupplier.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            
            <div class="form-group">
                <label for="title">Nama Info Supplier</label>
                <input require type="text" name="name" class="form-control" 
                value="{{old('name','')}}" maxlength="50">
            </div>

            <div class="form-group">
                <label for="title">Keterangan</label>
                <input require type="text" name="keterangan" class="form-control" 
                value="{{old('keterangan','')}}" maxlength="255">
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection


