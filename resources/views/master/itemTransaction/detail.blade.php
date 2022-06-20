@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Transaction item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemTransaction.index')}}">Transaksi item</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form method="POST" >
                @csrf
                @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Item Transaksi</label>
                <input readonly type="text" name="Name" class="form-control" 
                value="{{old('Name',$itemTransaction->Name)}}" >
            </div>

            <div class="form-group">
                <label for="title">Kode</label>
                <input readonly type="text" name="Code" class="form-control" 
                value="{{old('Code',$itemTransaction->Code)}}" >
            </div>

            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input readonly type="text" name="Description" class="form-control" 
                value="{{old('Description',$itemTransaction->Description)}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="button" href="{{route('itemTransaction.index')}}" class="btn btn-primary">Kembali</button>
        </div>
    </form>
</div>
@endsection
