@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Gudang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mGudang.index')}}">Gudang</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mGudang.show', [$mGudang->MGudangID])}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Gudang</label>
                <input required type="text" name="name" class="form-control" 
                value="{{old('cname',$mGudang->cname)}}" disabled>
            </div>
            <div class="form-group">
                <label for="title">Kode Gudang</label>
                <input required type="text" name="code" class="form-control" 
                value="{{old('ccode',$mGudang->ccode)}}" disabled>
            </div>
            <div class="form-group">
                <label>Perusahaan</label>
                <select required name="perusahaan" class="form-control select2bs4" style="width: 100%;" disabled>
                    <option value="0">--Pilih Perusahaan--</option>
                    @foreach($dataMPerusahaan as $data)
                        @if($data->MPerusahaanID == $mGudang->cidp)
                            <option selected value="{{$data->MPerusahaanID}}"{{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cnames}})</option>
                        @else
                            <option value="{{$data->MPerusahaanID}}"{{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->cnames}})</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kota</label>
                <select required name="kota" class="form-control select2bs4" style="width: 100%;" disabled>
                    <option value="0">--Pilih Kota--</option>
                    @foreach($dataMKota as $data)
                        @if($data->cidkota == $mGudang->cidkota)
                            <option selected value="{{$data->cidkota}}"{{$data->cname == $data->cidkota? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->ckode}})</option>
                        @else
                            <option value="{{$data->cidkota}}"{{$data->cname == $data->cidkota? 'selected' :'' }}>{{$data->cname}}<nbsp>({{$data->ckode}})</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kepala Gudang</label>
                <select name="kepala" class="form-control select2bs4" style="width: 100%;" disabled>
                    <option value="0">--Pilih Kota--</option>
                    @foreach($users as $data)
                        @if($data->id == $mGudang->UserIDKepalaDivisi)
                            <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                            <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>
        <!-- /.card-body -->
       <div class="card-footer">
            <a class="btn btn-default bg-info" href="{{route('mGudang.index')}}">
              Back
            </a>
        </div>
    </form>
</div>

@endsection
