@extends('layouts.home_master')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Tracing Item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Tracing Item</li>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tracing Item</h3>
                    
                    <a href="{{route('itemTracing.create')}}" class="btn btn-primary btn-responsive float-right">Tambah item Tracing
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
                              <th >#</th>
                              <th >Nama item Tracing</th>
                              <th >Handle</th>
                            </tr>
                          </thead>
                        <tbody>
                             @foreach($data as $key => $itemTrac)         
                             <tr>
                                <th scope="row">{{$itemTrac->ItemTracingID}}</th>
                                <td>{{$itemTrac->Name}}</td>                     

                                <td>  
                                
                                    <a class="btn btn-default bg-info" href="{{route('itemTracing.edit',[$itemTrac->ItemTracingID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                  
                                    <button type="button" class="btn btn-default bg-info" data-toggle="modal" data-target="#detail_{{$itemTrac->ItemTracingID}}">
                                      <i class="fas fa-eye"></i> 
                                    </button>
                                    <div class="modal fade" id="detail_{{$itemTrac->ItemTracingID}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Detail Pulau</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button> 
                                                </div>
                                                <div class="modal-body">
                                                        <p>Nama :{{$itemTrac->Name}}</p>
                                                        <p>Remarks :{{$itemTrac->Notes}}</p>
                                                 
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                 
                                    <form action="{{route('itemTracing.destroy',[$itemTrac->ItemTracingID])}}" method="POST" class="btn btn-responsive">
                                    @csrf
                                    @method('DELETE')
                                      <button action="{{route('itemTracing.destroy',[$itemTrac->ItemTracingID])}}" method="POST" class="btn btn-default bg-danger">
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
                              <th >#</th>
                              <th >Nama item Tracing</th>
                              <th >Handle</th>
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

