
@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Cek Selesai Nota
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Cek Selesai Nota</li>
<li class="breadcrumb-item active">Purchase Order</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari PO</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <form action="/checkPurchaseOrdere/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama NPP">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
<div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <form action="/checkPurchaseOrdere/searchdate/" method="get">
                <label>Tanggal Pembuatan Awal - Akhir:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="text" name="dateRangeSearch" class="form-control float-right" id="reservation" value="{{old('tanggalDibutuhkan','')}}" >
                    <button type="submit" class="btn btn-lg btn-default">
                    <i class="fa fa-search"></i>
                    </button>
                </div>                           
            </form>
        </div>
    </div>
</div>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permintaan Pembelian</h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Suplier</th>
                                <th scope="col">Alamat Suplier</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col">Status Approve</th>
                                <th scope="col">Proses</th>
                                <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($poKeluar != null)
                            @foreach($poKeluar as $purchaseOrder)
                            <tr>
                                <th scope="row" name='id'>{{$purchaseOrder->id}}</th>
                                <td>{{$purchaseOrder->name}}</td>
                                <td id="dengan-rupiah">@php echo "Rp " . number_format($purchaseOrder->totalHarga,2,',','.'); @endphp</td>
                                <td>{{$purchaseOrder->supplierName}}</td>
                                <td>{{$purchaseOrder->supplierAlamat}}</td>
                                <td>{{date("d-m-Y", strtotime($purchaseOrder->tanggalDibuat))}}</td>
                                @if($purchaseOrder->approved==0)
                                <td>Pending</td>
                                @elseif($purchaseOrder->approved==1)
                                <td>Approved</td>
                                @elseif($purchaseOrder->approved==2)
                                <td>Not Approved</td>
                                @endif
                                <td>
                                    @if($purchaseOrder->proses == 0)
                                    Belum
                                    @elseif($purchaseOrder->proses == 1)
                                    Sedang Diproses
                                    @endif
                                </td>
                                <td>  
                                <a href="{{route('checkPurchaseOrder.edit',[$purchaseOrder->id])}}" class="btn btn-primary btn-responsive">Cek Update</a>  
                                </td> 
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Suplier</th>
                                    <th scope="col">Alamat Suplier</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                    <th scope="col">Status Approve</th>
                                    <th scope="col">Proses</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
{{ $poKeluar->links('pagination::bootstrap-4') }}
@endsection
