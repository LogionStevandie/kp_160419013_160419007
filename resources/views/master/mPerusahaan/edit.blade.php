@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Perusahaan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mPerusahaan.index')}}">Perusahaan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mPerusahaan.update', [$mPerusahaan->MPerusahaanID])}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Perusahaan</label>
                <input required type="text" name="name" class="form-control" 
                value="{{old('cname', $mPerusahaan->cname)}}">
            </div>
            <div class="form-group">
                <label for="title">Kode Perusahaan</label>
                <input required type="text" name="names" class="form-control" 
                value="{{old('cnames',$mPerusahaan->cnames)}}" >
            </div>
            <div class="form-group">
                <label>Manager 1</label>
                <select name="manager1" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 1--</option>
                    @foreach($users as $data)
                        @if($data->id == $mPerusahaan->UserIDManager1)
                            <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                            <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Manager 2</label>
                <select name="manager2" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 2--</option>
                    @foreach($users as $data)
                        @if($data->id == $mPerusahaan->UserIDManager2)
                            <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                            <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title">Nomor NPWP</label>
                <input required type="text" name="NomorNPWP" class="form-control" 
                value="{{old('NomorNPWP',$mPerusahaan->NomorNPWP)}}">
            </div>
            <div class="form-group">
                <label for="title">Alamat NPWP</label>
                <input required type="text" name="AlamatNPWP" class="form-control" 
                value="{{old('AlamatNPWP',$mPerusahaan->AlamatNPWP)}}" >
            </div>

            <div class="form-group">
                <label for="title">Gambar Bendera Perusahaan </label> 
                <input type="file" class="form-control" name="image" placeholder="Choose image" id="image" src="{{asset($mPerusahaan->Gambar)}}" value="{{old('image',$mPerusahaan->Gambar)}}"
                accept="image/png, image/jpeg,image/jpg">
                <img src='{{asset($mPerusahaan->Gambar)}}' alt='' width='100'>
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
