@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
List Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">List-Supplier</li>
@endsection

@section('content')
<div class="container-fluid">
        <h2 class="text-center display-4">Search</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="/msuppliere/searchname/" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama Supplier">
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
                    <h3 class="card-title">List Supplier </h3>
                    
                    <a href="{{route('msupplier.create')}}" class="btn btn-primary btn-responsive float-right">Tambah Supplier
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
                              <th scope="col">#</th>
                              <th scope="col">Tax</th>
                              <th scope="col">Name</th>
                              <th scope="col">infoSupplierName</th>
                              <th scope="col">currencyName</th>
                              <th scope="col">paymentTermsName</th>
                              <th scope="col">kotaName</th>
                              <th scope="col">coaName</th>
                              <th scope="col">Contact Person</th>
                              <th scope="col">Handle</th>
                            </tr>
                          </thead>
                        <tbody>
                           @foreach($data as $supplier)          
                             <tr>
                                <th scope="row" name='id'>{{$supplier->SupplierID}}</th>
                                <td>{{$supplier->taxName}}</td>
                                <td>{{$supplier->Name}}</td>
                                <td>{{$supplier->infoSupplierName}}</td>
                                <td>{{$supplier->currencyName}}</td>
                                <td>{{$supplier->paymentTermsName}}</td>
                                <td>{{$supplier->kotaName}}</td>
                                <td>{{$supplier->coaName}}</td>
                                <td>{{$supplier->ContactPerson}}</td>
                                <td>  
                                
                                    <a class="btn btn-default bg-info" href="{{route('msupplier.edit',[$supplier->SupplierID])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                     <a href="{{route('msupplier.show',[$supplier->SupplierID])}}" class="btn btn-default bg-info"><i class="fas fa-eye"></i> </a> 

                                    
                                    <form action="{{route('msupplier.destroy',[$supplier->SupplierID])}}" method="POST" class="btn btn-responsive">
                                        @csrf
                                        @method('DELETE')
                                          <button action="{{route('msupplier.destroy',[$supplier->SupplierID])}}" class="btn btn-default bg-danger">
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
                              <th scope="col">#</th>
                              <th scope="col">Tax</th>
                              <th scope="col">Name</th>
                              <th scope="col">infoSupplierName</th>
                              <th scope="col">currencyName</th>
                              <th scope="col">paymentTermsName</th>
                              <th scope="col">kotaName</th>
                              <th scope="col">coaName</th>
                              <th scope="col">Contact Person</th>
                              <th scope="col">Handle</th>
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


