@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Kategori item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemCategory.index')}}">Kategori-item</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('itemCategory.update',[$itemCategory->ItemCategoryID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

           @csrf
                      @method('PUT')

                        <div class="form-group">
                           <label for="title">Nama Item Kategori</label>
                           <input require type="text" name="Name" class="form-control" 
                           value="{{old('Name',$itemCategory->Name)}}" maxlength="50">
                        </div>

                         <div class="form-group">
                           <label for="title">Remarks</label>
                           <input require type="text" name="remarks" class="form-control" 
                           value="{{old('Remarks',$itemCategory->Remarks)}}" maxlength="10">
                        </div>

                        <!--<div class="form-group">
                           <label for="title">NTB Debet COA</label>
                           <input require type="number" name="NTBDebetCOA" class="form-control" 
                           value="{{old('NTBDebetCOA',$itemCategory->NTBDebetCOA)}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">NTB Kredit COA</label>
                           <input require type="number" name="NTBKreditCOA" class="form-control" 
                           value="{{old('NTBKreditCOA',$itemCategory->NTBKreditCOA)}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VDebet COA</label>
                           <input require type="number" name="BillVDebetCOA" class="form-control" 
                           value="{{old('BillVDebetCOA',$itemCategory->BillVDebetCOA)}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VKredit COA</label>
                           <input require type="number" name="BillVKreditCOA" class="form-control" 
                           value="{{old('BillVKreditCOA',$itemCategory->BillVKreditCOA)}}" >
                        </div>

                         <div class="form-group">
                           <label for="title">Penjualan COA</label>
                           <input require type="number" name="PenjualanCOA" class="form-control" 
                           value="{{old('PenjualanCOA',$itemCategory->PenjualanCOA)}}" >
                        </div>-->


        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
