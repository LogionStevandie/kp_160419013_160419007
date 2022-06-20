@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Kota
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mKota.index')}}">Kota</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mKota.update', [$mKota->MKotaID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Cid Kota</label>
                <input required type="text" name="cid" maxlength="5" class="form-control" 
                value="{{old('cidKota',$mKota->cidkota)}}">
            </div>
            <div class="form-group">
                <label for="title">Kode Kota</label>
                <input required type="text" name="kode" maxlength="5" class="form-control" 
                value="{{old('ckode',$mKota->ckode)}}" >
            </div>
            <div class="form-group">
                <label for="title">Nama Kota</label>
                <input required type="text" name="name" maxlength="50" class="form-control" 
                value="{{old('cname',$mKota->cname)}}" >
            </div>
            <div class="form-group">
                <label>Pulau</label>
                <select required name="pulau" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Pulau--</option>
                    @foreach($dataMPulau as $data)
                        @if($data->cidpulau == $mKota->cidpulau)
                            <option selected value="{{$data->cidpulau}}"{{$data->cname == $data->cidpulau? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cidpulau}})</option>
                        @else
                            <option value="{{$data->cidpulau}}"{{$data->cname == $data->cidpulau? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cidpulau}})</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Provinsi</label>
                <select required name="prov" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Provinsi--</option>
                    @foreach($dataMProvinsi as $data)
                        @if($data->cidprov == $mKota->cidprov)
                            <option selected value="{{$data->cidprov}}"{{$data->cname == $data->cidprov? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cidprov}})</option>
                        @else
                            <option value="{{$data->cidprov}}"{{$data->cname == $data->cidprov? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cidprov}})</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection
