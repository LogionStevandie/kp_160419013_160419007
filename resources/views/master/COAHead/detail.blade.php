@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail COA Head
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('coaHead.index')}}">COA Head</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
               <div class="form-group">
                  <label for="title">Nama</label>
                  <input readonly type="text" maxlength="50" name="nama" class="form-control" 
                     value="{{old('nama',$cOAHead->Nama)}}" >
               </div>

         </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="button" href="{{route('coaHead.index')}}" class="btn btn-primary">Back</button>
        </div>
    </form>
</div>
@endsection
