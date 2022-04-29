@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Info Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('infoSupplier.index')}}">Info Supplier</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
   <form action="{{route('infoSupplier.update',[$infoSupplier->InfoSupplierID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Nama Info Supplier</label>
                <input require type="text" name="name" class="form-control" 
                value="{{old('name',$infoSupplier->name)}}" maxlength="50">
            </div>

            <div class="form-group">
                <label for="title">Keterangan</label>
                <input require type="text" name="keterangan" class="form-control" 
                value="{{old('keterangan',$infoSupplier->keterangan)}}" maxlength="255">
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection

