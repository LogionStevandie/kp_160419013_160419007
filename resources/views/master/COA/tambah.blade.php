@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah COA
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coa.index')}}">COA</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('coa.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nomor</label>
                <input require type="text" maxlength="50" name="nomor" class="form-control" 
                    value="{{old('nomor','')}}" >
            </div>
            <div class="form-group">
                <label for="title">Nama</label>
                <input require type="text" maxlength="50" name="nama" class="form-control" 
                    value="{{old('nama','')}}" >
            </div>
            <div class="form-group">
                <label>COA Head</label>
                <select required name="chead" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih COA Head--</option>
                    @foreach($dataCOAHead as $data)
                        <option value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>COA Detail</label>
                <select required name="cdet" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih COA Detail--</option>
                    @foreach($dataCOADetail as $data)
                        <option value="{{$data->Cdet}}"{{$data->Nama == $data->Cdet? 'selected' :'' }}>{{$data->Nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>  
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
