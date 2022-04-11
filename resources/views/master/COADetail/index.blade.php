@extends('layouts.home_master')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
COA Detail
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">COA-Detail</li>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">COA Detail</h3>
                    
                    <a href="{{route('coaDetail.create')}}" class="btn btn-primary btn-responsive float-right">Tambah COA Detail
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
                              <th>Coa Detail</th>
                              <th>Nama COA Head</th>
                              <th>Nama COA Detail</th>
                              <th>Keterangan</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <td>{{$d->Cdet}}</td>  
                                <td>{{$d->COAHeadName}}</td>  
                                <td>{{$d->CDet_Name}}</td>  
                                <td>{{$d->Keterangan}}</td>  
                                <td>  
                                
                                    <a class="btn btn-default bg-info" href="{{route('coaDetail.edit',[$d->Cdet])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                
                                    <form action="{{route('coaDetail.destroy',[$d->Cdet])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                          <button action="{{route('coaDetail.destroy',[$d->Cdet])}}" class="btn btn-default bg-danger">
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
                                <th>Coa Detail</th>
                                <th>Nama COA Head</th>
                                <th>Nama COA Detail</th>
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

@endsection


