@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail COA Detail
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coaDetail.index')}}">COA Detail</a></li>
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
                <label>COA Head</label>
                <select readonly name="coahead" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Pulau--</option>
                    @foreach($dataCOAHead as $data)
                        @if($data->CH_ID == $coaDetail->CoaHead)
                            <option selected value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                        @else
                            <option value="{{$data->CH_ID}}"{{$data->Nama == $data->CH_ID? 'selected' :'' }}>{{$data->Nama}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="form-group">
                    <label for="title">Nama</label>
                    <input readonly type="text" maxlength="50" name="cdet_name" class="form-control" 
                        value="{{old('cdet_name',$coaDetail->CDet_Name)}}" >
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="title">Keterangan</label>
                    <input readonly type="text" maxlength="50" name="keterangan" class="form-control" 
                        value="{{old('keterangan',$coaDetail->Keterangan)}}" >
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button readonly type="button" href="{{route('coaDetail.index')}}" class="btn btn-primary">Back</button>
        </div>
    </form>
</div>
@endsection
