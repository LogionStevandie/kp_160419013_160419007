
@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('msupplier.index')}}">Supplier</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
      <form action="{{route('msupplier.show',[$msupplier->SupplierID])}}" method="POST" >
        @csrf
        @method('PUT')
         <div class="card-body">
              <div class="form-group">
                        <div class="form-group">
                             <div class="form-group">
                             <label for="title">Info supplier</label>
                            <select name="infoSupplierID" class="form-control select2" disabled>
                                    <option value="">--Pilih supplier--</option>
                                    @foreach($infoSupplier as $key => $data)
                                    @if($data->InfoSupplierID==$msupplier->InfoSupplierID)
                                    <option selected value="{{$data->InfoSupplierID}}"{{$data->name == $data->InfoSupplierID? 'selected' :'' }}>{{$data->name}}</option>
                                    @else
                                    <option  value="{{$data->InfoSupplierID}}"{{$data->name == $data->InfoSupplierID? 'selected' :'' }}>{{$data->name}}</option>
                                    @endif
                                    @endforeach
                            </select>
                        </div>

                    

                        <div class="form-group">
                            <label for="title">Nama Supplier</label>
                           <input require type="text" name="name" class="form-control" 
                           value="{{old('Name',$msupplier->Name)}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="title">Alamat Supplier</label>
                           <input require type="text" name="alamat" class="form-control" 
                           value="{{old('Alamat',$msupplier->Alamat)}}" readonly>
                        </div>

                         <div class="form-group">
                            <label for="title">Kota</label>
                            <input require type="text" name="kota" class="form-control" 
                           value="{{old('Kota',$msupplier->Kota)}}" readonly>
                        </div>
                     
                        
                        <div class="form-group">
                            <label for="title">Kode Pos</label>
                            <input require type="number" name="kodePos" class="form-control" 
                           value="{{old('KodePos',$msupplier->KodePos)}}" readonly>
                        </div>


                        <div class="form-group">
                            <label for="title">Phone 1</label>
                            <input require type="number" name="phone1" class="form-control" 
                           value="{{old('Phone1',$msupplier->Phone1)}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="title">Phone 2</label>
                            <input require type="number" name="phone2" class="form-control" 
                           value="{{old('Phone2',$msupplier->Phone2)}}" readonly>
                        </div>

                        
                        <div class="form-group">
                            <label for="title">Fax 1</label>
                            <input require type="text" name="fax1" class="form-control" 
                           value="{{old('Fax1',$msupplier->Fax1)}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="title">Fax 2</label>
                            <input require type="text" name="fax2" class="form-control" 
                           value="{{old('Fax2',$msupplier->Fax2)}}" readonly>
                        </div>

                         <div class="form-group">
                            <label for="title">Contact Person</label>
                            <input require type="text" name="contactPerson" class="form-control" 
                           value="{{old('ContactPerson',$msupplier->ContactPerson)}}" readonly>
                        </div>

                         <div class="form-group">
                            <label for="title">email</label>
                            <input require type="text" name="email" class="form-control" 
                           value="{{old('Email',$msupplier->Email)}}" readonly>
                        </div>

                         <div class="form-group">
                            <label for="title">NPWP</label>
                            <input require type="number" name="NPWP" class="form-control" 
                           value="{{old('NPWP',$msupplier->NPWP)}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="title">Bank</label>
                            <input require type="text" name="bank" class="form-control" 
                           value="{{old('bank',$msupplier->bank)}}" readonly>
                        </div>
                    
                        
                         <div class="form-group">
                            <label for="title">No Rekening</label>
                            <input require type="number" name="noRekening" class="form-control" 
                           value="{{old('NoRekening',$msupplier->NoRekening)}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="title">note</label>
                            <input require type="text" name="note" class="form-control" 
                           value="{{old('Note',$msupplier->Note)}}" readonly>
                        </div>

                           <div class="form-group">
                            <label for="title">Atas Nama</label>
                            <input require type="text" name="atasNama" class="form-control" 
                           value="{{old('AtasNama',$msupplier->AtasNama)}}" readonly>
                        </div>

                           <div class="form-group">
                            <label for="title">Lokasi</label>
                            <input require type="text" name="lokasi" class="form-control" 
                           value="{{old('Lokasi',$msupplier->Lokasi)}}"readonly >
                        </div>

                           <div class="form-group">
                            <label for="title">Kode</label>
                            <input require type="text" name="kode" class="form-control" 
                           value="{{old('Kode',$msupplier->Kode)}}" readonly>
                        </div>

                          <div class="form-group">
                            <label for="title">Keterangan</label>
                            <input require type="text" name="keterangan" class="form-control" 
                           value="{{old('Keterangan',$msupplier->Keterangan)}}" readonly>
                        </div>


                          <div class="form-group">
                            <label for="title">Nama NPWP</label>
                            <input require type="text" name="namaNPWP" class="form-control" 
                           value="{{old('NamaNPWP',$msupplier->NamaNPWP)}}" readonly>
                        </div>

                     

                        <div class="form-group">
                            <label for="title">KTP</label>
                            <input require type="text" name="KTP" class="form-control" 
                           value="{{old('KTP',$msupplier->KTP)}}" readonly>
                        </div>

                            

                           <div class="card-footer">
                            <a class="btn btn-default bg-info" href="{{route('msupplier.index')}}">
                            Kembali
                            </a>
                        </div>
        <!-- /.card-body -->
        
        </div>
        
    </form>
</div>
@endsection

