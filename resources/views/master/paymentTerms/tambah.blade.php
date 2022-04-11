@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Ketentuan Pembayaran
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('paymentTerms.index')}}">Ketentuan-Pembayaran</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('paymentTerms.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama</label>
                <input required type="text" name="name" maxlength="50" class="form-control" value="{{old('name','')}}">
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required type="text" name="deskripsi" maxlength="512" class="form-control" value="{{old('deskripsi','')}}" >
            </div>
            <div class="form-group">
                <label for="title">Jumlah Hari</label>
                <input required type="number" name="days" min="1" class="form-control" value="{{old('deskripsi','')}}" >
            </div>
        
            
            <div class="form-group">
                <label for="title">Pembelian / Penjualan</label><br>
                <div class="icheck-primary d-inline">
                    <input type="radio" id="radioPrimary1" name="isPembelian" checked value="1"{{'1' == old('isPembelian','')? 'checked' :'0' }}>
                    <label for="radioPrimary1">Pembelian
                    </label>
                </div>
                <div class="icheck-primary d-inline">
                    <input type="radio" id="radioPrimary2" name="isPenjualan" value="1"{{'1' == old('isPenjualan','')? 'checked' :'0' }}>
                    <label for="radioPrimary2">Penjualan
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Pembayaran</label>
                <select required name="prov" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Pembayaran--</option>
                    @foreach($dataPayment as $data)
                        <option value="{{$data->PaymentID}}"{{$data->Name == $data->PaymentID? 'selected' :'' }}>{{$data->Name}}</option>
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
