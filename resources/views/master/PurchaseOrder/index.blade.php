@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Purchase Order
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Pembuatan Permintaan</li>
<li class="breadcrumb-item active">Purchase-Order</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari PO</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <form action="/purchaseOrdere/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama PO">
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
            <form action="/purchaseOrdere/searchdate/" method="get">
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
                    <h3 class="card-title">List-Purchase Order</h3>
                    
                    <a href="{{route('purchaseOrder.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Purchase Order
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </a> 
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Nama</th>
                               <th scope="col">Proses</th>
                              <th scope="col">Status Approved</th>
                              <th scope="col">Handle</th>
                            </tr>
                          </thead>
                         <tbody>
                            @foreach($data as $purchaseOrder)
                            <tr >
                              <th scope="row" name='id'>{{$purchaseOrder->id}}</th>
                              <td>{{$purchaseOrder->name}}</td>
                              <td>{{$purchaseOrder->proses}}</td>
                              @if($purchaseOrder->approved==0)
                              <td>Pending</td>
                              @elseif($purchaseOrder->approved==1)
                              <td>Approved</td>
                              @elseif($purchaseOrder->approved==2)
                              <td>Not Approved</td>
                              @endif
                            
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('purchaseOrder.show',[$purchaseOrder->id])}}">
                                    <i class="fas fa-eye"></i> 
                                </a>
                                @if($purchaseOrder->approved==0)
                                    <a class="btn btn-info btn-sm" href="{{route('purchaseOrder.edit',[$purchaseOrder->id])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('purchaseOrder.destroy',[$purchaseOrder->id])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" action="{{route('purchaseOrder.destroy',[$purchaseOrder->id])}}">
                                            <i class="fas fa-trash"></i> 
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="/PurchaseOrdere/print/{{$purchaseOrder->id}}" method="get" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i>Print</a>
                                
                            </td>  
                            </tr>
                            @endforeach
                           
                          </tbody>
                        <tfoot>
                            <tr>
                             <th scope="col">#</th>
                              <th scope="col">Nama</th>
                               <th scope="col">Proses</th>
                              <th scope="col">Status Approved</th>
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
{{ $data->links('pagination::bootstrap-4') }}
@endsection