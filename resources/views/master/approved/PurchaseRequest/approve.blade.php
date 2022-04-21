
@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Permintaan Pembelian
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Persetujuan Pembelian</li>
<li class="breadcrumb-item active">Permintaan Pembelian</li>
<li class="breadcrumb-item active">Approve</li>
@endsection

@section('content')

<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form method="POST" action="{{route('approvedPurchaseRequest.update',[$purchaseRequest->id])}}" >
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col" colspan="3"><h2>PERSETUJUAN PERMINTAAN PEMBELIAN</h2></th>
                                    <th scope="col" colspan="3">
                                        Nama Npp : {{$purchaseRequest->name}}<br>
                                        Tanggal pembuatan : {{$purchaseRequest->created_on}}

                                    </th>
                                    </tr>
                                </thead>
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"colspan="6"cellspacing="3" >
                                                                        
                                    @foreach($dataGudang as $data)
                                        @if($data->MGudangID == $purchaseRequest->MGudangID)
                                        Gudang :{{$data->cname}} <nbsp> ({{$purchaseRequest->MGudangID}}) <br> 
                                        @endif 
                                    @endforeach
                                    Jenis permintaan : {{$purchaseRequest->jenisProses}} <br>
                                    Tanggal dibutuhkan : {{$purchaseRequest->tanggalDibutuhkan}}<br>
                                    Tanggal batas akhir : {{$purchaseRequest->tanggalAkhirDibutuhkan}}
                                    </th>
                                    </tr>
                                </thead>
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>

                                        @foreach($prd as $data) 
                                        <tr>
                                            @if($data->idPurchaseRequest==$purchaseRequest->id)
                                            <th scope="row">{{$data->id}}</th>
                                            <th scope="row">{{$data->ItemName}}</th>
                                            <td>{{$data->jumlah}}</td>
                                            <td>{{$data->harga}}</td>              
                                            <td>{{$data->keterangan_jasa}}</td>                                          
                                            <td>{{$data->jumlah * $data->harga}}</td>                                          
                                            @endif
                                        </tr>
                                        @endforeach
                                
                                </tbody>
                            </table>

                        </div>
                        <div class="form-group">
                            <label for="title">Pembelian / Penjualan</label><br>
                            <div class="icheck-primary d-inline">
                                <input id="setuju"  type="radio" name="approve" value="1"{{'1' == old('approved','')? 'checked' :'' }}>
                                <label class="form-check-label" for="setuju">Setuju</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input id="tdkSetuju"  type="radio" name="approve" value="2"{{'2'== old('approved','')? 'checked' :'' }}>
                                <label class="form-check-label" for="tdkSetuju">Tidak Setuju</label><br>
                            </div><br><br>
                            <div id="ket">
                                <label class="form-check-label" >Keterangan :</label><br>
                                <textarea rows="3" required type="text" name="keterangan"class="form-control" value="{{old('keterangan','')}}" id="txt"></textarea>
                            </div>
                        </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>


<script type="text/javascript">
$('body').on("click", "#setuju", function (){

         $("#ket").hide();
         document.getElementById("txt").value = "-";
});
$('body').on("click", "#tdkSetuju", function (){

        $("#ket").show();
});
</script>
@endsection
