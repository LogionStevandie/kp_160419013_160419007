@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit COA Detail
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coaDetail.index')}}">COA-Detail</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('coaDetail.update',[$coaDetail->Cdet])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>COA Head</label>
                <select required name="coahead" class="form-control select2bs4" style="width: 100%;">
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
                    <input require type="text" maxlength="50" name="cdet_name" class="form-control" 
                        value="{{old('cdet_name',$coaDetail->CDet_Name)}}" >
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="title">Keterangan</label>
                    <input require type="text" maxlength="50" name="keterangan" class="form-control" 
                        value="{{old('keterangan',$coaDetail->Keterangan)}}" >
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
