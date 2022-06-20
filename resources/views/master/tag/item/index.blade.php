@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Tag Barang Values
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Tag-Barang-Values</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Cari Nama Tag Item</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <form action="/itemTagValuese/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama Tag Barang">
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
                    <h3 class="card-title">Tag Barang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                         <thead>
                            <tr>
                              <th>ID</th>
                              <th>Nama Barang</th>
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
                                
                                    <a class="btn btn-default bg-info" href="{{route('itemTagValues.edit',[$data->ItemID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                     <a href="{{route('itemTagValues.show',[$data->ItemID])}}" class="btn btn-default bg-info"><i class="fas fa-eye"></i> </a> 

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                             <tr>
                              <th>ID</th>
                              <th>Nama Barang</th>
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
{{ $dataItem->links('pagination::bootstrap-4') }}
@endsection