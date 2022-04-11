@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Supplier</h1>
        <br>
        <a href="{{route('msupplier.create')}}" class="btn btn-primary btn-responsive">Tambah Supplier
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
        </a> 
    </div>
</div>
<!-- Content Row -->
<div class="container">
  <div class="row">
    <div class="col-12">
      <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">TaxID</th>
                              <th scope="col">Contact Person</th>
                              <th scope="col">Handle</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($data as $supplier)
                            <tr >
                              <th scope="row" name='id'>{{$supplier->SupplierID}}</th>
                              <td>{{$supplier->TaxID}}</td>
                              <td>{{$supplier->ContactPerson}}</td>
                              <td>  
                               <a href="{{route('msupplier.edit',[$supplier->SupplierID])}}" class="btn btn-primary btn-responsive">Edit </a>
                                  <form action="{{route('msupplier.destroy',[$supplier->SupplierID])}}" method="POST" class="btn btn-responsive">
                                    @csrf
                                    @method('DELETE')
                                    <button action="{{route('msupplier.destroy',[$supplier->SupplierID])}}" method="POST" class="btn btn-secondary btn-danger">                          
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                       <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                    </svg>
                                    </button>
                                   </form>
                              
                              </td>
                              
                            </tr>
                            @endforeach
                           
                          </tbody>
                        </table>
   </div>
  </div>
</div>

@endsection