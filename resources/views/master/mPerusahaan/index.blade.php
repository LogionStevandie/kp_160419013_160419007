@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Perusahaan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Perusahaan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perusahaan</h3>
                    
                    <a href="{{route('mPerusahaan.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Perusahaan
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
                                <th>Nama Perusahaan</th>
                                <th>Manager 1</th>
                                <th>Manager 2</th>
                                <th>Action</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <th>{{$d->MPerusahaanID}}</th>
                                <td>{{$d->cname}}</td>                                   
                                <td>
                                    @foreach($dataUser as $user)
                                        @if($user->id == $d->UserIDManager1)
                                            {{$user->name}}
                                        @endif
                                    @endforeach
                                </td>    
                                <td>
                                    @foreach($dataUser as $user)
                                        @if($user->id == $d->UserIDManager2)
                                            {{$user->name}}
                                        @endif
                                    @endforeach
                                </td>    
                                <td>  
                              
                                    <a class="btn btn-default bg-info" href="{{route('mPerusahaan.edit',[$d->MPerusahaanID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-default bg-info" data-toggle="modal" data-target="#detail_{{$d->MPerusahaanID}}">
                                     <i class="fas fa-eye"></i> 
                                    </button>
                                    <div class="modal fade" id="detail_{{$d->MPerusahaanID}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Detail Perusahaan</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button> 
                                                </div>
                                                <div class="modal-body">
                                                        <p>Nama perusahaan :{{$d->cname}}</p>
                                                        <p>Kode:{{$d->cnames}}</p>
                                                        <p>Manager 1 :
                                                            @foreach($dataUser as $user)
                                                                @if($user->id == $d->UserIDManager1)
                                                                    <p>{{$user->name}}</p>
                                                                @endif
                                                            @endforeach
                                                        </p>
                                                         <p>Manager 2 :
                                                            @foreach($dataUser as $user)
                                                                @if($user->id == $d->UserIDManager2)
                                                                    <p>{{$user->name}}</p>
                                                                @endif
                                                            @endforeach
                                                        </p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <form action="{{route('mPerusahaan.destroy',[$d->MPerusahaanID])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                         <button action="{{route('mPerusahaan.destroy',[$d->MPerusahaanID])}}" method="POST" class="btn btn-default bg-danger">
                                            <i class="fas fa-trash"></i> 
                                         </button>
                                    </form>  
                                </td>
                            </tr>   
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama Perusahaan</th>
                                <th>Manager 1</th>
                                <th>Manager 2</th>
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