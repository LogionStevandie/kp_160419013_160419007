@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Gudang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Gudang</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="/mGudange/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama Gudang">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <br>

                <form action="/mGudange/searchcode/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchcode" placeholder="Kode Gudang">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
        </div>
</div>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gudang</h3>
                    
                    <a href="{{route('mGudang.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Gudang
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
                                <th>Kode</th>
                                <th>Nama Gudang</th>
                                <th>Nama Kota</th>
                                <th>User Kepala Gudang</th>
                                <th>Tag</th>
                                <th>Handle</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <th>{{$d->MGudangID}}</th>
                                <td>{{$d->ccode}}</td>
                                <td>{{$d->cname}}</td>
                                <td>{{$d->kotaName}}</td>
                                <td>{{$d->manager}}</td>
                                <td>
                                    @foreach($dataTag as $tag)
                                        @if($tag->MGudangID == $d->MGudangID)
                                            <span class="badge bg-primary">{{$tag->cname}}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>  
                                    <a class="btn btn-default bg-info" href="{{route('mGudang.show',[$d->MGudangID])}}">
                                        <i class="fas fa-eye"></i> 
                                    </a>
                                    <a class="btn btn-default bg-info" href="{{route('mGudang.edit',[$d->MGudangID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-default bg-danger" data-toggle="modal" data-target="#delete_{{$d->MGudangID}}">
                                     <i class="fas fa-trash"></i> 
                                    </button>

                                     <div class="modal fade" id="delete_{{$d->MGudangID}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h4 class="modal-title">Konfirmasi</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button> 
                                                </div>
                                               
                                                <div class="modal-body">
                                                     Apakah anda yakin mau menghapus  "{{$d->cname}}"
                                                </div>
                                            
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                                                    <form action="{{route('mGudang.destroy',[$d->MGudangID])}}" method="POST" class="btn btn-responsive">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-default bg-danger" action="{{route('mGudang.destroy',[$d->MGudangID])}}">
                                                            Hapus
                                                        </button>
                                                    
                                                    </form>   
                                                </div>
                                                
                                            </div>
                                        <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                     </div>
                                   
                                </td>
                            </tr>   
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama Gudang</th>
                                <th>Nama Kota</th>
                                <th>User Kepala Gudang</th>
                                <th>Tag</th>
                                <th>Handle</th>
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