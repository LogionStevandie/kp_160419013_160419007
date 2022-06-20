@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Mata uang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mCurrency.index')}}">Mata uang</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
      <form action="{{route('mCurrency.store')}}" method="POST" >
       @csrf
        <div class="card-body">
            
                <div class="form-group">
                    <label for="title">Nama Mata uang</label>
                    <input require type="text" name="name" class="form-control" 
                    value="{{old('name','')}}"  maxlength="50">
                </div>

                <div class="form-group">
                    <label for="title">Kode</label>
                    <input require type="text" name="code" class="form-control" 
                    value="{{old('code','')}}"  maxlength="50">
                </div>

                <div class="form-group">
                    <label for="title">Negara</label>
                    <input require type="text" name="country" class="form-control" 
                    value="{{old('country','')}}" maxlength="50">
                </div>

                <div class="form-group">
                    <label for="title">Harga</label>
                    <input require type="number" name="price" class="form-control" 
                    value="{{old('price','')}}">
                </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>
@endsection

