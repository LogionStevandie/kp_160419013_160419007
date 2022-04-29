@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Tag Gudang Values
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Tag-Gudang-Values</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Search</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="/tagValuesMGudange/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama Gudang">
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
                    <h3 class="card-title">Tag-Gudang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                             <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama Gudang</th>
                                <th>Tag</th>
                                <th>Action</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                             <tr>
                                <td>{{$d->MGudangID}}</td>
                                <td>{{$d->ccode}}</td>
                                <td>{{$d->cname}}</td>
                                <td>
                                    @foreach($dataTag as $tag)
                                        @if($tag->MGudangID == $d->MGudangID)
                                            <span class="badge bg-primary">{{$tag->cname}}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>  
                                    <a class="btn btn-default bg-info" href="{{route('tagValuesMGudang.show',[$d->MGudangID])}}">
                                        <i class="fas fa-eye"></i> 
                                    </a>
                                    <a class="btn btn-default bg-info" href="{{route('tagValuesMGudang.edit',[$d->MGudangID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>   
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama Gudang</th>
                                <th>Tag</th>
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