@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Tambah Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('msupplier.index')}}">Supplier</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{route('msupplier.store')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="title">Info supplier</label>
                        <select class="form-control select2" style="width: 100%;" name="infoSupplierID">
                            <option value="">--Pilih supplier--</option>
                            @foreach($infoSupplier as $key => $data)
                            <option value="{{$data->InfoSupplierID}}" {{$data->name == $data->InfoSupplierID? 'selected' :'' }}>{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>





                    <div class="form-group">
                        <label for="title">Nama Supplier</label>
                        <input require type="text" name="name" class="form-control" maxlength="50" value="{{old('Name','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Alamat Supplier</label>
                        <input require type="text" name="alamat" class="form-control" maxlength="100" value="{{old('Alamat','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Kota</label>
                        <input require type="text" name="kota" class="form-control" maxlength="50" value="{{old('Kota','')}}">
                    </div>



                    <div class="form-group">
                        <label for="title">Kode Pos</label>
                        <input require type="number" name="kodePos" class="form-control" maxlength="50" value="{{old('KodePos','')}}">
                    </div>


                    <div class="form-group">
                        <label for="title">Phone 1</label>
                        <input require type="number" name="phone1" class="form-control" maxlength="50" value="{{old('Phone1','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Phone 2</label>
                        <input require type="number" name="phone2" class="form-control" maxlength="50" value="{{old('Phone2','')}}">
                    </div>


                    <div class="form-group">
                        <label for="title">Fax 1</label>
                        <input require type="text" name="fax1" class="form-control" maxlength="50"  value="{{old('Fax1','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Fax 2</label>
                        <input require type="text" name="fax2" class="form-control" maxlength="50" value="{{old('Fax2','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Contact Person</label>
                        <input require type="text" name="contactPerson" class="form-control" maxlength="50" value="{{old('ContactPerson','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">email</label>
                        <input require type="text" name="email" class="form-control" maxlength="50" value="{{old('Email','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">NPWP</label>
                        <input require type="text" name="NPWP" class="form-control" maxlength="20" value="{{old('NPWP','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Bank</label>
                        <input require type="text" name="bank" class="form-control" maxlength="50" value="{{old('bank','')}}">

                    </div>

                    <div class="form-group">
                        <label for="title">No Rekening</label>
                        <input require type="number" name="noRekening" class="form-control" maxlength="50" value="{{old('NoRekening','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">note</label>
                        <input require type="text" name="note" class="form-control" maxlength="2000" value="{{old('Note','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Atas Nama</label>
                        <input require type="text" name="atasNama" class="form-control" maxlength="50" value="{{old('AtasNama','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Lokasi</label>
                        <input require type="text" name="lokasi" class="form-control" maxlength="50" value="{{old('Lokasi','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Kode</label>
                        <input require type="text" name="kode" class="form-control" maxlength="100" value="{{old('Kode','')}}">
                    </div>

                    <div class="form-group">
                        <label for="title">Keterangan</label>
                        <input require type="text" name="keterangan" class="form-control" maxlength="2000" value="{{old('Keterangan','')}}">
                    </div>


                    <div class="form-group">
                        <label for="title">Nama NPWP</label>
                        <input require type="text" name="namaNPWP" class="form-control"maxlength="128" value="{{old('NamaNPWP','')}}">
                    </div>


                    <div class="form-group">
                        <label for="title">KTP</label>
                        <input require type="text" name="KTP" class="form-control" maxlength="24" value="{{old('KTP','')}}">
                    </div>



                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>

                </div>

        </form>
    </div>
    @endsection