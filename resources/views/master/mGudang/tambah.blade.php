@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Gudang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mGudang.index')}}">Gudang</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mGudang.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Gudang</label>
                <input required type="text" name="name" maxlength="50" class="form-control" 
                value="{{old('cname','')}}">
            </div>
            <div class="form-group">
                <label for="title">Kode Gudang</label>
                <input required type="text" name="code" maxlength="8" class="form-control" 
                value="{{old('ccode','')}}" >
            </div>
            <div class="form-group">
                <label>Perusahaan</label>
                <select required name="perusahaan" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Perusahaan--</option>
                    @foreach($dataMPerusahaan as $data)
                        <option value="{{$data->MPerusahaanID}}"{{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cnames}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kota</label>
                <select required name="kota" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Kota--</option>
                    @foreach($dataMKota as $data)
                        <option value="{{$data->cidkota}}"{{$data->cname == $data->cidkota? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->ckode}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kepala Gudang</label>
                <select name="kepala" class="form-control select2bs4" style="width: 100%;" placeholder="Optional">
                    <option value="0">--Pilih Kepala Gudang--</option>
                    @foreach($users as $data)
                        <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>

@endsection
