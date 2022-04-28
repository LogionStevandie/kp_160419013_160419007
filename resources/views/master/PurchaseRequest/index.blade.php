@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Purchase Request
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Pembuatan Permintaan</li>
<li class="breadcrumb-item active">Purchase-Request</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List-Purchase Request</h3>
                    
                    <a href="{{route('purchaseRequest.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Purchase Request
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
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gudang</th>
                                <th>Total Harga</th>
                                <th>Disetujui</th>   
                                <th>Proses</th>   
                                <th>Keterangan</th>   
                                <th>Action</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <th>{{$d->id}}</th>
                                <td>{{$d->name}}</td>
                                <td>{{$d->gudangName}}</td>
                                <td>{{$d->totalHarga}}</td>
                                <td>
                                    @if($d->approved == 0)
                                        Belum Diproses
                                    @elseif($d->approved == 1)
                                        Disetujui
                                    @elseif($d->approved == 2)
                                        Ditolak
                                    @endif
                                </td>
                                <td>
                                    @if($d->proses == 0)
                                        Belum Diproses
                                    @elseif($d->proses == 1)
                                        Sedang Diproses
                                    @elseif($d->proses == 2)
                                        Selesai
                                    @endif
                                </td>
                                <td>{{$d->keterangan}}</td>

                                     <td >
                                           <a class="btn btn-primary btn-sm" href="{{route('purchaseRequest.show',[$d->id])}}">
                                                <i class="fas fa-eye"></i> 
                                            </a>
                                            @if($d->approved == 0)
                                                <a class="btn btn-info btn-sm" href="{{route('purchaseRequest.edit',[$d->id])}}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{route('purchaseRequest.destroy',[$d->id])}}" method="POST" class="btn btn-responsive">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" action="{{route('purchaseRequest.destroy',[$d->id])}}">
                                                        <i class="fas fa-trash"></i> 
                                                    </button>
                                                </form>                                         
                                            @endif
                                            
                                             <a href="PurchaseRequeste/print/{{$d->id}}" method="get" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                                    
                                    </td>  
                                  
                                
                            </tr>   
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gudang</th>
                                <th>Total Harga</th>
                                <th>Disetujui</th>   
                                <th>Proses</th>   
                                <th>Keterangan</th>   
                                <th>Action</th>
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
{{ $dataItem->links('pagination::bootstrap-4') }}
@endsection