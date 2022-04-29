@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail COA
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coa.index')}}">COA</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

        <div class="form-group">
                <label for="title">Nomor</label>
                <input readonly type="text" maxlength="50" name="nomor" class="form-control" 
                    value="{{old('nomor',$cOA->Nomor)}}" >
            </div>
            <div class="form-group">
                <label for="title">Nama</label>
                <input readonly type="text" maxlength="50" name="nama" class="form-control" 
                    value="{{old('nama',$cOA->Nama)}}" >
            </div>
            
            <div class="form-group">
                <label>COA Head</label>
                <select readonly name="coahead" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Pulau--</option>
                    @foreach($dataCOAHead as $data)
                        @if($data->CH_ID == $cOA->CHead)
                            <option selected value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                        @else
                            <option value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>COA Detail</label>
                <select readonly name="cdet" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih COA Detail--</option>
                    @foreach($dataCOADetail as $data)
                    @if($data->Cdet == $cOA->Cdet)
                        <option selected value="{{$data->Cdet}}"{{$data->Nama == $data->Cdet? 'selected' :'' }}>{{$data->Nama}}</option>
                    @else
                        <option value="{{$data->Cdet}}"{{$data->Nama == $data->Cdet? 'selected' :'' }}>{{$data->Nama}}</option>
                    @endif
                    @endforeach
                </select>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="button" href="{{route('coa.index')}}" class="btn btn-primary">Back</button>
        </div>
    </form>
</div>
@endsection
