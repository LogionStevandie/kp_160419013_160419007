@extends('layouts.home_master')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
COA Head
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">COA-Head</li>
@endsection


@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari Nama COA Head</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="/coaHeade/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama COA Head">
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
                    <h3 class="card-title">COA </h3>
                    
                    <a href="{{route('coaHead.create')}}" class="btn btn-primary btn-responsive float-right">Tambah COA
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
                              <th>Nama COA Head</th>
                              <th>Handle</th>
                            </tr>
                          </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <th>{{$d->CH_ID}}</th>
                                <td>{{$d->Nama}}</td>  
                                <td>  
                                    <a class="btn btn-default bg-info" href="{{route('coaHead.show',[$d->CH_ID])}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-default bg-info" href="{{route('coaHead.edit',[$d->CH_ID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                
                                    <form action="{{route('coaHead.destroy',[$d->CH_ID])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                          <button action="{{route('coaHead.destroy',[$d->CH_ID])}}" class="btn btn-default bg-danger">
                                            <i class="fas fa-trash"></i> 
                                          </button>
                                        @csrf
                                    </form>  
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                               <th>#</th>
                               <th>Nama Kategori item</th>
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
{{ $dataItem->links('pagination::bootstrap-4') }}
@endsection


