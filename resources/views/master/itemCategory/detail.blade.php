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
                           <label for="title">Nama Item Kategori</label>
                           <input require type="text" name="Name" class="form-control" 
                           value="{{old('Name',$itemCategory->Name)}}" disabled>
                        </div>

                         <div class="form-group">
                           <label for="title">Remarks</label>
                           <input require type="text" name="remarks" class="form-control" 
                           value="{{old('Remarks',$itemCategory->Remarks)}}" disabled>
                        </div>

                        <div class="form-group">
                           <label for="title">NTB Debet COA</label>
                           <input require type="number" name="NTBDebetCOA" class="form-control" 
                           value="{{old('NTBDebetCOA',$itemCategory->NTBDebetCOA)}}" disabled>
                        </div>

                        <div class="form-group">
                           <label for="title">NTB Kredit COA</label>
                           <input require type="number" name="NTBKreditCOA" class="form-control" 
                           value="{{old('NTBKreditCOA',$itemCategory->NTBKreditCOA)}}" disabled>
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VDebet COA</label>
                           <input require type="number" name="BillVDebetCOA" class="form-control" 
                           value="{{old('BillVDebetCOA',$itemCategory->BillVDebetCOA)}}" disabled>
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VKredit COA</label>
                           <input require type="number" name="BillVKreditCOA" class="form-control" 
                           value="{{old('BillVKreditCOA',$itemCategory->BillVKreditCOA)}}" disabled>
                        </div>

                         <div class="form-group">
                           <label for="title">Penjualan COA</label>
                           <input require type="number" name="PenjualanCOA" class="form-control" 
                           value="{{old('PenjualanCOA',$itemCategory->PenjualanCOA)}}" disabled>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
