@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Stok Awal Barang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('stokAwal.index')}}">Stok Awal Barang</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('stokAwal.show', [$stokAwal->id])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="title">Nama</label>
                <input disabled type="text" name="name" maxlength="255" class="form-control" 
                value="{{old('name',$stokAwal->name)}}">
            </div>

            <div class="form-group"> 
                <label for="lastName">Tanggal Pembuatan</label>
                <input disabled required name="tanggalDibuat" type="date" class="form-control" id="tanggalDibuat" placeholder="" required="" value="{{$stokAwal->tanggalDibuat}}">
                <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
            </div>

            <div class="form-group">
                <label for="lastName">Pilih Gudang</label> 
                <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangID" disabled>
                    <option value="">
                        --Pilih Gudang--
                    </option>
                    @foreach($dataGudang as $key => $data)
                        @if($data->MGudangID == $stokAwal->MGudangID)
                            <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @else
                            <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endif
                    @endforeach
            
                </select>
            </div>

            <div class="form-group">
                <label for="lastName">Pilih Barang</label> 
                <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="ItemID" name="ItemID">
                    <option value="">
                        --Pilih Barang--
                    </option>
                    @foreach($dataBarang as $key => $data)
                        @if($data->ItemID == $stokAwal->ItemID)
                            <option selected name="idItem" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}</option>
                        @else
                            <option name="idItem" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title">Jumlah Barang</label>
                <input required  type="number" step=".01" min="1"  name="jumlah" class="form-control" 
                value="{{old('jumlah',$stokAwal->jumlah)}}" disabled>
            </div>
            
            <div class="form-group">
                <label for="title">Keterangan</label>
                <input required type="text" name="keterangan" maxlength="500" class="form-control" 
                value="{{old('keterangan',$stokAwal->keterangan)}}" disabled>
            </div>

        </div>

    </form>
</div>
@endsection   


