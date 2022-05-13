@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Perusahaan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mPerusahaan.index')}}">Perusahaan</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mPerusahaan.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Perusahaan</label>
                <input required type="text" name="name" class="form-control" 
                value="{{old('cname','')}}">
            </div>
            <div class="form-group">
                <label for="title">Kode Perusahaan</label>
                <input required type="text" name="names" class="form-control" 
                value="{{old('cnames','')}}" >
            </div>
            <div class="form-group">
                <label>Manager 1</label>
                <select name="manager1" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 1--</option>
                    @foreach($users as $data)
                        <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Manager 2</label>
                <select name="manager2" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 2--</option>
                    @foreach($users as $data)
                        <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Nomor NPWP</label>
                <input required type="text" name="NomorNPWP" class="form-control" 
                value="{{old('NomorNPWP','')}}">
            </div>
            <div class="form-group">
                <label for="title">Alamat NPWP</label>
                <input required type="text" name="AlamatNPWP" class="form-control" 
                value="{{old('AlamatNPWP','')}}" >
            </div>

            <div class="form-group">
                <label for="title">Gambar Bendera Perusahaan</label>
                <input required type="file" class="form-control" name="image" placeholder="Choose image" id="image" value="{{old('image','')}}"
                accept="image/png, image/jpeg,image/jpg">
                @error('image')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
                <small>Maksimum Unggah Gambar 2Mb</small>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

@endsection
