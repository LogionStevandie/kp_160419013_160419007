@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Edit Pajak
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('tax.index')}}">Pajak</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->

        <form action="{{route('tax.update',[$tax->TaxID])}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">

                <div class="form-group">
                    <label for="title">Nama Pajak</label>
                    <input require type="text" name="name" class="form-control" value="{{old('Name',$tax->Name)}}">
                </div>

                <div class="form-group">
                    <label for="title">Deskripsi</label>
                    <input require type="text" name="deskripsi" class="form-control" value="{{old('Deskripsi',$tax->Deskripsi)}}">
                </div>

                <div class="form-group">
                    <label for="title">Persen Pajak</label>
                    <input require type="number" name="taxpercent" class="form-control" value="{{old('TaxPercent',$tax->TaxPercent)}}">
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    @endsection