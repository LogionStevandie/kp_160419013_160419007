@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Pembayaran
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('payment.index')}}">Pembayaran</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form  method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama</label>
                <input readonly type="text" name="name" maxlength="20" class="form-control" value="{{old('name',$payment->Name)}}">
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input readonly type="text" name="deskripsi" maxlength="100" class="form-control" value="{{old('deskripsi',$payment->Deskripsi)}}" >
            </div>
            

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" href="{{route('payment.index')}}" class="btn btn-primary">Kembali</button>
        </div>
    </form>
</div>

@endsection
