@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah COA Detail
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coaDetail.index')}}">COA-Detail</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('coaDetail.store')}}" method="POST" >
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>COA Head</label>
                <select required name="coahead" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih COA Head--</option>
                    @foreach($dataCOAHead as $data)
                        <option value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="title">Nama</label>
                    <input require type="text" maxlength="50" name="cdet_name" class="form-control" 
                        value="{{old('cdet_name','')}}" >
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="title">Keterangan</label>
                    <input require type="text" maxlength="50" name="keterangan" class="form-control" 
                        value="{{old('keterangan','')}}" >
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
