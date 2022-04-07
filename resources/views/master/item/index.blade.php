@extends('layouts.home_master')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active"> Item</li>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> item </h3>
                    
                    <a href="{{route('item.create')}}" class="btn btn-primary btn-responsive float-right">Tambah item
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
                              <th>#</th>
                              <th>Nama Item</th>
                              <th>Tag</th>
                              <th>Handle</th>
                            </tr>
                          </thead>
                        <tbody>
                             @foreach($dataItem as $key => $data)            
                             <tr>
                                <th scope="row">{{$data->ItemID}}</th>
                                <td>{{$data->ItemName}}</td>                     
                                
                                <td>
                                  @foreach($dataTag as $tag) 
                                    @if($tag->ItemID == $data->ItemID)
                                    <span class="badge badge-pill badge-success"> {{$tag->Name}}</span>
                                      
                                    @endif
                                  @endforeach
                                  
                                </td>
                                <td>  
                                
                                    <a class="btn btn-default bg-info" href="{{route('item.edit',[$data->ItemID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                     <a href="{{route('item.show',[$data->ItemID])}}" class="btn btn-default bg-info"><i class="fas fa-eye"></i> </a> 

                                 
                                    
                                    <form action="{{route('item.destroy',[$data->ItemID])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                          <button action="{{route('item.destroy',[$data->ItemID])}}" method="POST" class="btn btn-default bg-danger">
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
                              <th>Nama Item</th>
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

@endsection


