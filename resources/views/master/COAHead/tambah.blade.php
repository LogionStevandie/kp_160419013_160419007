@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah COA Head
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coaHead.index')}}">COA-Head</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('coaHead.store')}}" method="POST" >
        @csrf
        <div class="card-body">

            <div class="form-group">
               <div class="form-group">
                  <label for="title">Nama</label>
                  <input require type="text" maxlength="50" name="nama" class="form-control" 
                     value="{{old('nama','')}}" >
               </div>

         </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
