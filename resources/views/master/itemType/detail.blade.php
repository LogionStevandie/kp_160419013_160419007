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
    <h1 class="h3 mb-0 text-gray-800">item Type -> Detail</h1>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Detail item Type
                </div>
                 
                <div class="card-body">
            
                    <form  method="POST" >
                      @csrf
             
                        <div class="form-group">
                           <label for="title">Nama Item Type</label>
                           <input require type="text" name="Name" class="form-control" 
                           value="{{old('Name',$itemType->Name)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Notes</label>
                           <input require type="text" name="Notes" class="form-control" 
                           value="{{old('Notes',$itemType->Notes)}}" disabled>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
