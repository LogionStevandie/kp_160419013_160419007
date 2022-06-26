@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Detail Penyesuaian Stok Barang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Nota</li>
<li class="breadcrumb-item"><a href="{{route('adjustmentStock.index')}}">Penyesuaian Stok Barang</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{route('adjustmentStock.index')}}">
            <div class="card-body">
                <div class="form-group">
                    <label for="lastName">Name</label>
                    <input disabled type="text" class="form-control" id="tanggalDibuat" placeholder="" value="{{$adjustmentStock->Name}}">
                    <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
                </div>
                <div class="form-group">
                    <label for="lastName">Tanggal Pembuatan</label>
                    <input disabled name="tanggalDibuat" type="date" class="form-control" id="tanggalDibuat" placeholder="" required="" value="{{$adjustmentStock->Tanggal}}">
                    <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
                </div>



                <div class="form-group">
                    <label for="lastName">Pilih Gudang</label>
                    <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangID">
                        <option value="">
                            --Pilih Gudang--
                        </option>
                        @foreach($dataGudang as $key => $data)
                            @if($data->MGudangID == $adjustmentStockDetail[0]->MGudangID)
                            <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                            @else
                            <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                            @endif
                        @endforeach

                    </select>
                </div>



                <div class="form-group">
                    <label for="lastName">Pilih Barang</label>
                    <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="ItemID" name="ItemID">
                        <option value="pilih">
                            --Pilih Barang--
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Deskripsi</label>
                    <input disabled type="text" name="keterangan" class="form-control" value="{{old('jumlah',$adjustmentStock->Description)}}" maxlength="500">
                </div>

                <div class="form-group">
                    <label for="title">Jumlah Stok Barang Awal</label>
                    <input disabled type="number" step=".01" min="1" id="stokAwalBarang" name="QuantityAwal" class="form-control" value="{{old('QuantityAwal',$adjustmentStockDetail[0]->QuantityAwal)}}">
                </div>

                <div class="form-group">
                    <label for="title">Jumlah Stok Barang Baru</label>
                    <input disabled type="number" step=".01" min="1" name="QuantityBaru" class="form-control" value="{{old('QuantityBaru',$adjustmentStockDetail[0]->QuantityBaru)}}">
                </div>



            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Kembali</button>
            </div>
        </form>
    </div>

    @endsection