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
                            <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
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
                                <th scope="col">Nama Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Tipe Transaksi</th><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Item</th>
                                <th scope="col">Awal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Akhir</th>
                            </tr>
                          </thead>
                        <tbody>
                            @if($dataReport != null)      
                                @php    
                                $awal = 0;               
                                $akhirStok = 0;                              
                                @foreach($dataReport as $stok)     
                                    @if($loop->first)
                                        @foreach($dataReportSingleSebelum as $stoksebelum)
                                            <tr>  
                                                <td scope="col">{{ $loop->index + 1 }}</td> $awal = $awal + 1;
                                                <td scope="col">{{$data->Name}}</td>
                                                <td scope="col">{{date("d-m-Y", strtotime($data->Date))}}</td>
                                                <td scope="col">
                                                @if($data->AdjustmentID != null)
                                                    Penyesuaian Item
                                                @elseif($data->SuratJalanID != null)
                                                    Transaksi Menggunakan Surat Jalan
                                                @elseif($data->StokAwalID != null)
                                                    Stok Awal Gudang
                                                @else
                                                    Transaksi
                                                @endif
                                                </td><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                                <td scope="col">{{$data->tipeTransaksi}}</td>

                                                <td >{{$stok->ItemName}}</td>
                                                <td>{{$stok->totalQuantity}}</td>                 
                                                <td>0</td>                 
                                                <td>0</td>                 
                                                <td>{{$stok->totalQuantity}}</td>   
                                                $akhirStok = 30;               
                                            </tr>
                                        @endforeach
                                    @endif
                                    
                                    <tr>  
                                        <td scope="col">{{ $loop->index + 1 + $awal }}</td>
                                        <td scope="col">{{$data->Name}}</td>
                                        <td scope="col">{{date("d-m-Y", strtotime($data->Date))}}</td>
                                        <td scope="col">
                                          @if($data->AdjustmentID != null)
                                            Penyesuaian Item
                                          @elseif($data->SuratJalanID != null)
                                            Transaksi Menggunakan Surat Jalan
                                          @elseif($data->StokAwalID != null)
                                            Stok Awal Gudang
                                          @else
                                            Transaksi
                                          @endif
                                        </td><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                        <td scope="col">{{$data->tipeTransaksi}}</td>

                                        <td >{{$stok->ItemName}}</td>
                                        <td>{{$akhirStok}}</td>    
                                        @if($stok->Quantity > 0)
                                            <td>{{$stok->Quantity}}</td>                 
                                            <td>0</td> 
                                            <td>{{$stok->Quantity->sum($akhirStok)}}</td>
                                        @else
                                            <td>0</td>                 
                                            <td>{{abs($stok->Quantity)}}</td> 
                                            <td>{{$stok->Quantity->sum($akhirStok)}}</td>
                                        @endif                
                                                         
                                    </tr>
                                @endforeach
                                @endphp
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Tipe Transaksi</th><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Item</th>
                                <th scope="col">Awal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Akhir</th>
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


