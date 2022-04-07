@extends('layouts.home_master')

@section('judul')
Detail Perusahaan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mPerusahaan.index')}}">Perusahaan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mProvinsi.update',[$mProvinsi->MProvinsiID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Cid Provinsi : </label>
                <br>
                <p>{{old('cidprov',$mProvinsi->cidprov)}}</p>
            </div>
            <div class="form-group">
                <label for="title">Nama Provinsi : </label>
                <br>
                <p>{{old('cname',$mProvinsi->cname)}}</p>
            </div>

        </div>

        <div class="card-footer">
            <a class="btn btn-default bg-info" href="{{route('mProvinsi.index')}}">
              <i class="fas fa-arrow-alt-left"></i>
            </a>
        </div>
        <!-- /.card-body -->

    </form>
</div>
@endsection