@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Perusahaan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mPerusahaan.index')}}">Perusahaan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mPerusahaan.update', [$mPerusahaan->MPerusahaanID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Perusahaan</label>
                <input required type="text" name="name" class="form-control" 
                value="{{old('cname', $mPerusahaan->cname)}}">
            </div>
            <div class="form-group">
                <label for="title">Kode Perusahaan</label>
                <input required type="text" name="names" class="form-control" 
                value="{{old('cnames',$mPerusahaan->cnames)}}" >
            </div>
            <div class="form-group">
                <label>Manager 1</label>
                <select name="manager1" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 1--</option>
                    @foreach($users as $data)
                        @if($data->id == $mPerusahaan->UserIDManager1)
                            <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                            <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Manager 2</label>
                <select name="manager2" class="form-control select2bs4" style="width: 100%;">
                    <option value="0">--Pilih Manager 2--</option>
                    @foreach($users as $data)
                        @if($data->id == $mPerusahaan->UserIDManager2)
                            <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                            <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

@endsection
