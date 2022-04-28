@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Gudang Area Simpan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Gudang-Area-Simpan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gudang Area Simpan</h3>
                    
                    <a href="{{route('mGudangAreaSimpan.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Gudang Area Simpan
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
                                <th>Action</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <th>{{$d->MGudangAreaSimpanID}}</th>
                                <td>{{$d->cname}}</td>
                                <td>  
                                    <a class="btn btn-default bg-info" href="{{route('mGudangAreaSimpan.show',[$d->MGudangAreaSimpanID])}}">
                                        <i class="fas fa-eye"></i> 
                                    </a>
                                    <a class="btn btn-default bg-info" href="{{route('mGudangAreaSimpan.edit',[$d->MGudangAreaSimpanID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('mGudangAreaSimpan.destroy',[$d->MGudangAreaSimpanID])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-default bg-danger" action="{{route('mGudangAreaSimpan.destroy',[$d->MGudangAreaSimpanID])}}">
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
                                <th>Nama</th>
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
{{ $data->links('pagination::bootstrap-4') }}
@endsection