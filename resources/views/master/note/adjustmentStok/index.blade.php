@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Terima Barang Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Nota</li>
<li class="breadcrumb-item active">Penyesuaian Stok Barang</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari Nota</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <form action="/stokAwale/searchname/" method="get">
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
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List-Penyesuaian Stok Barang</h3>
                    
                    <a href="{{route('adjustmentStock.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Penyesuaian Stok Barang  
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
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col">Gudang</th>
                                <th scope="col">Barang</th>
                                <th scope="col">Stok Awal</th>
                                <th scope="col">Stok Penyesuaian</th>
                                <th scope="col">Handle</th>
                            </tr>
                          </thead>
                         <tbody>
                            @foreach($data as $d)
                            <tr >
                            <th scope="row" name='id'>{{$d->ItemAdjustmentID}}</th>
                            <td>{{$d->Name}}</td>
                            <td>{{$d->Tanggal}}</td>   
                            <td>{{$d->gudangName}}</td>
                            <td>{{$d->itemName}}<nbsp>({{$d->unitName}})</td>
                            <td>{{$d->QuantityAwal}}</td>                 
                            <td>{{$d->QuantityBaru}}</td>                 
                            <td>  
                                <a class="btn btn-default bg-info" href="{{route('adjustmentStock.show',[$d->ItemAdjustmentID])}}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a class="btn btn-default bg-info" href="{{route('adjustmentStock.edit',[$d->ItemAdjustmentID])}}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{route('adjustmentStock.destroy',[$d->ItemAdjustmentID])}}" method="POST" class="btn btn-responsive">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-default bg-danger" action="{{route('adjustmentStock.destroy',[$d->ItemAdjustmentID])}}">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                </form>  
                            </td>
                              
                            </tr>
                            @endforeach
                           
                          </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col">Gudang</th>
                                <th scope="col">Barang</th>
                                <th scope="col">Stok Awal</th>
                                <th scope="col">Stok Penyesuaian</th>
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