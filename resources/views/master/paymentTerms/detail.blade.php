@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Ketentuan Pembayaran
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('paymentTerms.index')}}">Ketentuan Pembayaran</a></li>
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
                <label for="title">Nama</label>
                <input readonly type="text" name="name" maxlength="50" class="form-control" value="{{old('name',$paymentTerms->Name)}}">
            </div>
            <div class="form-group">
                <label for="title">Deskripsi</label>
                <input readonly type="text" name="deskripsi" maxlength="512" class="form-control" value="{{old('deskripsi',$paymentTerms->Deskripsi)}}" >
            </div>
            <div class="form-group">
                <label for="title">Jumlah Hari</label>
                <input readonly type="number" name="days" min="1" class="form-control" value="{{old('days',$paymentTerms->Days)}}" >
            </div>
        
            
            <div class="form-group">
                <label for="title">Pembelian / Penjualan</label><br>
                
                <div class="icheck-primary d-inline">
                    @if($paymentTerms->isPembelian == 1)
                        <input readonly type="radio" id="radioPrimary1" name="isPembelian" checked value="1"{{'1' == old('isPembelian','')? 'checked' :'0' }}>
                    @else
                        <input readonly type="radio" id="radioPrimary1" name="isPembelian" value="1"{{'1' == old('isPembelian','')? 'checked' :'0' }}>
                    @endif
                    <label for="radioPrimary1">Pembelian
                    </label>
                </div>
                <div class="icheck-primary d-inline">
                    @if($paymentTerms->isPembelian == 1)
                        <input readonly type="radio" id="radioPrimary2" name="isPembelian" value="0"{{'1' == old('isPembelian','')? 'checked' :'0' }}>
                    @else
                        <input readonly type="radio" id="radioPrimary2" name="isPembelian" checked value="0"{{'1' == old('isPembelian','')? 'checked' :'0' }}>
                    @endif
                    <label for="radioPrimary2">Penjualan
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Pembayaran</label>
                <select disabled name="paymentID" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Pembayaran--</option>
                    @foreach($dataPayment as $data)
                        @if($data->PaymentID == $paymentTerms->PaymentID)
                            <option selected value="{{$data->PaymentID}}"{{$data->Name == $data->PaymentID? 'selected' :'' }}>{{$data->Name}}</option>
                        @else
                            <option value="{{$data->PaymentID}}"{{$data->Name == $data->PaymentID? 'selected' :'' }}>{{$data->Name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="button" href="{{route('paymentTerms.index')}}" class="btn btn-primary">Kembali</button>
        </div>
    </form>
</div>

@endsection
