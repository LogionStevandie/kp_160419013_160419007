@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Kategori item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemCategory.index')}}">Kategori item</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('itemCategory.store')}}" method="POST" >
        @csrf
        <div class="card-body">

 <div class="form-group">
                           <label for="title">Nama Item Kategori</label>
                           <input require type="text" name="Name" class="form-control" 
                           value="{{old('Name','')}}" >
                        </div>

                         <div class="form-group">
                           <label for="title">Remarks</label>
                           <input require type="text" name="remarks" class="form-control" 
                           value="{{old('Remarks','')}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">NTB Debet COA</label>
                           <input require type="number" name="NTBDebetCOA" class="form-control" 
                           value="{{old('NTBDebetCOA','')}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">NTB Kredit COA</label>
                           <input require type="number" name="NTBKreditCOA" class="form-control" 
                           value="{{old('NTBKreditCOA','')}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VDebet COA</label>
                           <input require type="number" name="BillVDebetCOA" class="form-control" 
                           value="{{old('BillVDebetCOA','')}}" >
                        </div>

                        <div class="form-group">
                           <label for="title">Bill VKredit COA</label>
                           <input require type="number" name="BillVKreditCOA" class="form-control" 
                           value="{{old('BillVKreditCOA','')}}" >
                        </div>

                         <div class="form-group">
                           <label for="title">Penjualan COA</label>
                           <input require type="number" name="PenjualanCOA" class="form-control" 
                           value="{{old('PenjualanCOA','')}}" >
                        </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
