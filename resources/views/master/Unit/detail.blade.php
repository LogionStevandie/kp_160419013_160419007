@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Satuan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('unit.index')}}">Satuan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('unit.show',[$unit->UnitID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama</label>
                <input required type="text" name="name" maxlength="50" class="form-control" value="{{old('name',$unit->Name)}}" disabled>
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="deskripsi" maxlength="100" class="form-control" value="{{old('deskripsi',$unit->Deskripsi)}}" disabled>
            </div>
            

        </div>
        <!-- /.card-body -->
          <div class="card-footer">
            <button type="button" href="{{route('unit.index')}}" class="btn btn-primary">Back</button>
        </div>
    </form>
</div>

@endsection
