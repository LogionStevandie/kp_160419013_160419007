@extends('layouts.home_master')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

@section('judul')
Kartu Stok
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active">Kartu Stok</li>
@endsection


@section('content')
<div class="container-fluid">
    <h2 class="text-center display-4">Search Gudang</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="/kartuStok/searchgudang/" method="get">
                <div class="input-group">
                    <select class="form-control selectpicker col-md-8" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudangTujuan" name="searchgudangid">
                        <option value="">
                            --Semua Gudang--
                        </option>
                        @foreach($dataGudang as $data)
                        <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endforeach

                    </select>
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
                    <h3 class="card-title">Kartu Stok</h3>
                    <a href="{{url('/kartuStok/searchLengkap/')}}" class="btn btn-primary btn-responsive float-right">Kartu Stok Lengkap
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                        </svg>
                    </a>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Gudang</th>
                                <th scope="col">Item</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Jumlah Barang</th>
                               <!-- <th scope="col">Handle</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataReport as $stok)
                            <tr>

                                <td>{{$stok->gudangName}}</td>
                                <td>{{$stok->ItemName}}</td>
                                <td>
                                    @foreach($dataItem as $item)
                                    @if($stok->ItemID == $item->ItemID)
                                    {{$item->Name}}
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    {{$stok->totalQuantity}}
                                </td>
                                <!--
                                <td>

                                    <a class="btn btn-default bg-info" href=" /kartuStoke/report/{{$stok->MGudangID}}/{{$stok->ItemID}}" method="get">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="/kartuStoke/print/{{$stok->MGudangID}}/{{$stok->ItemID}}" method="get" rel="noopener" target="_blank" class="btn btn-default">
                                        <i class="fas fa-print"></i>
                                        Print
                                    </a>
                                </td>
                                -->
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">Gudang</th>
                                <th scope="col">Item</th>
                                <th scope="col">Satuan</th>
                                <th scope="col">Jumlah Barang</th>
                                <!--<th scope="col">Handle</th>-->

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

@endsection