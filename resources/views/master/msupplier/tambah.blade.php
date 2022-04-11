@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>
 <?php 
$currentUrl = Route::current()->getName();  //buat dapetno nama directory nya / route yang diapakek
//echo($currentUrl);
?>
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Supplier -> Tambah</h1>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Supplier item
                </div>
                 
                <div class="card-body">
            
                    <form action="{{route('msupplier.store')}}" method="POST" >
                      @csrf

                       <div class="form-group">
                             <label for="title">Info supplier</label>
                            <select name="infoSupplierID" class="form-control">
                                    <option value="">--Pilih supplier--</option>
                                    @foreach($infoSupplier as $key => $data)
                                    <option  value="{{$data->InfoSupplierID}}"{{$data->name == $data->InfoSupplierID? 'selected' :'' }}>{{$data->name}}</option>
                                    @endforeach
                            </select>
                        </div>

                        

                        <div class="form-group">
                             <label for="title">Mcurrency</label>
                            <select name="mCurrencyID" class="form-control">
                                    <option value="">--Pilih MCurrency--</option>
                                    @foreach($MCurrency as $key => $data)
                                    <option  value="{{$data->MCurrencyID}}"{{$data->name == $data->MCurrencyID? 'selected' :'' }}>{{$data->name}}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Tax</label>
                            <select name="taxID" class="form-control">
                                    <option value="">--Pilih Tax--</option>
                                    @foreach($Tax as $key => $data)
                                    <option value="{{$data->TaxID}}"{{$data->Name == $data->TaxID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="title">Payment Terms</label>
                            <select name="PaymentTermsID" class="form-control">
                                    <option value="">--Pilih Payment Terms--</option>
                                    @foreach($PaymentTerms as $key => $data)
                                    <option value="{{$data->PaymentTermsID}}"{{$data->Name == $data->PaymentTermsID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>

                        </div>

                         <div class="form-group">
                            <label for="title">COA</label>
                            <select name="COAID" class="form-control">
                                    <option value="">--Pilih COA--</option>
                                    @foreach($COA as $key => $data)
                                    <option value="{{$data->COAID}}"{{$data->Nama == $data->COAID? 'selected' :'' }}>{{$data->Nama}}</option>
                                    @endforeach
                            </select>

                        </div>
                     

                        <div class="form-group">
                            <label for="title">Nama Supplier</label>
                           <input require type="text" name="name" class="form-control" 
                           value="{{old('Name','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Alamat Supplier</label>
                           <input require type="text" name="alamat" class="form-control" 
                           value="{{old('Alamat','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Kota</label>
                            <input require type="text" name="kota" class="form-control" 
                           value="{{old('Kota','')}}" >
                        </div>
                     
                        

                        <div class="form-group">
                            <label for="title">Kode Pos</label>
                            <input require type="text" name="kodePos" class="form-control" 
                           value="{{old('KodePos','')}}" >
                        </div>


                        <div class="form-group">
                            <label for="title">Phone 1</label>
                            <input require type="text" name="phone1" class="form-control" 
                           value="{{old('Phone1','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Phone 2</label>
                            <input require type="text" name="phone2" class="form-control" 
                           value="{{old('Phone2','')}}" >
                        </div>

                        
                        <div class="form-group">
                            <label for="title">Fax 1</label>
                            <input require type="text" name="fax1" class="form-control" 
                           value="{{old('Fax1','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Fax 2</label>
                            <input require type="text" name="fax2" class="form-control" 
                           value="{{old('Fax2','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Contact Person</label>
                            <input require type="text" name="contactPerson" class="form-control" 
                           value="{{old('ContactPerson','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">email</label>
                            <input require type="text" name="email" class="form-control" 
                           value="{{old('Email','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">NPWP</label>
                            <input require type="text" name="NPWP" class="form-control" 
                           value="{{old('NPWP','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Rekening Bank</label>
                            <input require type="text" name="rekeningBank" class="form-control" 
                           value="{{old('RekeningBank','')}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">No Rekening</label>
                            <input require type="text" name="noRekening" class="form-control" 
                           value="{{old('NoRekening','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">note</label>
                            <input require type="text" name="note" class="form-control" 
                           value="{{old('Note','')}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Atas Nama</label>
                            <input require type="text" name="atasNama" class="form-control" 
                           value="{{old('AtasNama','')}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Lokasi</label>
                            <input require type="text" name="lokasi" class="form-control" 
                           value="{{old('Lokasi','')}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Kode</label>
                            <input require type="text" name="kode" class="form-control" 
                           value="{{old('Kode','')}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">Keterangan</label>
                            <input require type="text" name="keterangan" class="form-control" 
                           value="{{old('Keterangan','')}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">saldo DP</label>
                            <input require type="number" name="saldoDP" class="form-control" 
                           value="{{old('SaldoDP','')}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">Nama NPWP</label>
                            <input require type="text" name="namaNPWP" class="form-control" 
                           value="{{old('NamaNPWP','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">SKT</label>
                            <input require type="text" name="SKT" class="form-control" 
                           value="{{old('SKT','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">SPPKP</label>
                            <input require type="text" name="SPPKP" class="form-control" 
                           value="{{old('SPPKP','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">KTP</label>
                            <input require type="text" name="KTP" class="form-control" 
                           value="{{old('KTP','')}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Mkota</label>
                            <select name="mKotaID" class="form-control">
                                    <option value="">--Pilih kota--</option>
                                    @foreach($MKota as $key => $data)
                                    <option value="{{$data->MKotaID}}"{{$data->cname == $data->MKotaID? 'selected' :'' }}>{{$data->cname}}</option>
                                    @endforeach
                            </select>
                        </div>


                           <div class="form-group">
                             <label for="title">Petani</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Petani" value="1"{{'1' == old('Petani','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Petani" value="0"{{'0'== old('Petani','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>
                       
        
                        <div class="form-group">
                            <label for="title">Khusus</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Khusus" value="1"{{'1' == old('Khusus','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Khusus" value="0"{{'0'== old('Khusus','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>
                        <button class="btn btn-primary">Tambah</button>
   
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
