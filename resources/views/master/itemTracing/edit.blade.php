@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Tracing item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemTracing.index')}}">Tracing item</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')

<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('itemTracing.update',[$itemTracing->ItemTracingID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Item Tracing</label>
                <input require type="text" name="Name" class="form-control" 
                value="{{old('Name',$itemTracing->Name)}}" >
            </div>

            <div class="form-group">
                <label for="title">Notes</label>
                <input require type="text" name="Notes" class="form-control" 
                value="{{old('Notes',$itemTracing->Notes)}}" >
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection

