@extends('layouts.home_master')

@section('judul')
Detail Pulau
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('mPulau.index')}}">Pulau</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('mPulau.update',[$mPulau->MPulauID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Cid pulau : </label>
                <br>
                <p>{{old('cidpulau',$mPulau->cidpulau)}}</p>
            </div>
            <div class="form-group">
                <label for="title">Nama Pulau : </label>
                <br>
                <p>{{old('cname',$mPulau->cname)}}</p>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        <!-- /.card-body -->

    </form>
</div>
@endsection