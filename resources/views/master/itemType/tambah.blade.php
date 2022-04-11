@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah Tipe item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemType.index')}}">Tipe item</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
     <form action="{{route('itemType.store')}}" method="POST" >
    @csrf
        <div class="card-body">
            
                         <div class="form-group">
                           <label for="title">Nama Tipe Item</label>
                           <input require type="text" name="Name" class="form-control" 
                           value="{{old('Name','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Notes</label>
                           <input require type="text" name="Notes" class="form-control" 
                           value="{{old('Notes','')}}" >
                        </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection

