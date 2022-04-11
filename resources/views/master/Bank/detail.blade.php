@extends('layouts.home_master')
<?php 
$currentUrl = Route::current()->getName();  //buat dapetno nama directory nya / route yang diapakek

?>
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Item Tracing -> Detail</h1>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Edit Item Tracing
                </div>
                 
                <div class="card-body">
            
                    <form action="{{route('tax.update',[$tax->TaxID])}}" method="POST" >
                      @csrf
                      @method('PUT')



                         <div class="form-group">
                           <label for="title">Nama tax</label>
                           <input require type="text" name="name" class="form-control" 
                           value="{{old('Name',$tax->Name)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Deskripsi</label>
                           <input require type="text" name="deskripsi" class="form-control" 
                           value="{{old('Deskripsi',$tax->Deskripsi)}}" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="title">Tax Percent</label>
                           <input require type="number" name="taxpercent" class="form-control" 
                           value="{{old('TaxPercent',$tax->TaxPercent)}}" disabled>
                        </div>

             
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
