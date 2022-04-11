@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Supplier -> Edit</h1>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Edit Supplier
                </div>
                 
                <div class="card-body">
            
                    <form action="{{route('msupplier.update',[$msupplier->SupplierID])}}" method="POST" >
                      @csrf
                      @method('PUT')

                     
                         
                       <div class="form-group">
                             <label for="title">Info supplier</label>
                            <select name="infoSupplierID" class="form-control">
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
                             <label for="title">Mcurrency</label>
                            <select name="mCurrencyID" class="form-control">
                                    <option value="">--Pilih MCurrency--</option>
                                    @foreach($MCurrency as $key => $data)
                                    @if($data->MCurrencyID== $msupplier->MCurrencyID)
                                    <option selected value="{{$data->MCurrencyID}}"{{$data->name == $data->MCurrencyID? 'selected' :'' }}>{{$data->name}}</option>
                                    @else
                                    <option  value="{{$data->MCurrencyID}}"{{$data->name == $data->MCurrencyID? 'selected' :'' }}>{{$data->name}}</option>
                                    @endif
                                    @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Tax</label>
                            <select name="taxID" class="form-control">
                                    <option value="">--Pilih Tax--</option>
                                    @foreach($Tax as $key => $data)
                                    @if($data->TaxID==$msupplier->TaxID)
                                    <option selected value="{{$data->TaxID}}"{{$data->Name == $data->TaxID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @else
                                    <option value="{{$data->TaxID}}"{{$data->Name == $data->TaxID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endif
                                    @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="title">Payment Terms</label>
                            <select name="PaymentTermsID" class="form-control">
                                    <option value="">--Pilih Payment Terms--</option>
                                    @foreach($PaymentTerms as $key => $data)
                                    @if($data->PaymentTermsID==$msupplier->PaymentTermsID)
                                    <option selected value="{{$data->PaymentTermsID}}"{{$data->Name == $data->PaymentTermsID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @else
                                    <option value="{{$data->PaymentTermsID}}"{{$data->Name == $data->PaymentTermsID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endif

                                    @endforeach
                            </select>

                        </div>

                         <div class="form-group">
                            <label for="title">COA</label>
                            <select name="COAID" class="form-control">
                                    <option value="">--Pilih COA--</option>
                                    @foreach($COA as $key => $data)
                                    @if($data->COAID==$msupplier->COAID)
                                    <option value="{{$data->COAID}}"{{$data->Nama == $data->COAID? 'selected' :'' }}>{{$data->Nama}}</option>
                                    @else
                                    <option selected value="{{$data->COAID}}"{{$data->Nama == $data->COAID? 'selected' :'' }}>{{$data->Nama}}</option>
                                    @endif
                                    @endforeach
                            </select>

                        </div>
                     

                        <div class="form-group">
                            <label for="title">Nama Supplier</label>
                           <input require type="text" name="name" class="form-control" 
                           value="{{old('Name',$msupplier->Name)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Alamat Supplier</label>
                           <input require type="text" name="alamat" class="form-control" 
                           value="{{old('Alamat',$msupplier->Alamat)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Kota</label>
                            <input require type="text" name="kota" class="form-control" 
                           value="{{old('Kota',$msupplier->Kota)}}" >
                        </div>
                     
                        

                        <div class="form-group">
                            <label for="title">Kode Pos</label>
                            <input require type="text" name="kodePos" class="form-control" 
                           value="{{old('KodePos',$msupplier->KodePos)}}" >
                        </div>


                        <div class="form-group">
                            <label for="title">Phone 1</label>
                            <input require type="text" name="phone1" class="form-control" 
                           value="{{old('Phone1',$msupplier->Phone1)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Phone 2</label>
                            <input require type="text" name="phone2" class="form-control" 
                           value="{{old('Phone2',$msupplier->Phone2)}}" >
                        </div>

                        
                        <div class="form-group">
                            <label for="title">Fax 1</label>
                            <input require type="text" name="fax1" class="form-control" 
                           value="{{old('Fax1',$msupplier->Fax1)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Fax 2</label>
                            <input require type="text" name="fax2" class="form-control" 
                           value="{{old('Fax2',$msupplier->Fax2)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Contact Person</label>
                            <input require type="text" name="contactPerson" class="form-control" 
                           value="{{old('ContactPerson',$msupplier->ContactPerson)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">email</label>
                            <input require type="text" name="email" class="form-control" 
                           value="{{old('Email',$msupplier->Email)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">NPWP</label>
                            <input require type="text" name="NPWP" class="form-control" 
                           value="{{old('NPWP',$msupplier->NPWP)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">Rekening Bank</label>
                            <input require type="text" name="rekeningBank" class="form-control" 
                           value="{{old('RekeningBank',$msupplier->RekeningBank)}}" >
                        </div>

                         <div class="form-group">
                            <label for="title">No Rekening</label>
                            <input require type="text" name="noRekening" class="form-control" 
                           value="{{old('NoRekening',$msupplier->NoRekening)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">note</label>
                            <input require type="text" name="note" class="form-control" 
                           value="{{old('Note',$msupplier->Note)}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Atas Nama</label>
                            <input require type="text" name="atasNama" class="form-control" 
                           value="{{old('AtasNama',$msupplier->AtasNama)}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Lokasi</label>
                            <input require type="text" name="lokasi" class="form-control" 
                           value="{{old('Lokasi',$msupplier->Lokasi)}}" >
                        </div>

                           <div class="form-group">
                            <label for="title">Kode</label>
                            <input require type="text" name="kode" class="form-control" 
                           value="{{old('Kode',$msupplier->Kode)}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">Keterangan</label>
                            <input require type="text" name="keterangan" class="form-control" 
                           value="{{old('Keterangan',$msupplier->Keterangan)}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">saldo DP</label>
                            <input require type="number" name="saldoDP" class="form-control" 
                           value="{{old('SaldoDP',$msupplier->SaldoDP)}}" >
                        </div>

                          <div class="form-group">
                            <label for="title">Nama NPWP</label>
                            <input require type="text" name="namaNPWP" class="form-control" 
                           value="{{old('NamaNPWP',$msupplier->NamaNPWP)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">SKT</label>
                            <input require type="text" name="SKT" class="form-control" 
                           value="{{old('SKT',$msupplier->SKT)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">SPPKP</label>
                            <input require type="text" name="SPPKP" class="form-control" 
                           value="{{old('SPPKP',$msupplier->SPPKP)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">KTP</label>
                            <input require type="text" name="KTP" class="form-control" 
                           value="{{old('KTP',$msupplier->KTP)}}" >
                        </div>

                        <div class="form-group">
                            <label for="title">Mkota</label>
                            <select name="mKotaID" class="form-control">
                                    <option value="">--Pilih kota--</option>
                                    @foreach($MKota as $key => $data)
                                    @if($data->MKotaID==$msupplier->MKotaID)
                                    <option value="{{$data->MKotaID}}"{{$data->cname == $data->MKotaID? 'selected' :'' }}>{{$data->cname}}</option>
                                    @else
                                    <option selected value="{{$data->MKotaID}}"{{$data->cname == $data->MKotaID? 'selected' :'' }}>{{$data->cname}}</option>
                                    @endif
                                    @endforeach
                                    
                            </select>
                        </div>


                           <div class="form-group">
                             <label for="title">Petani</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Petani" value="1"{{'1' == old('Petani',$msupplier->Petani)? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Petani" value="0"{{'0'== old('Petani',$msupplier->Petani)? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>
                       
        
                        <div class="form-group">
                            <label for="title">Khusus</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Khusus" value="1"{{'1' == old('Khusus',$msupplier->Khusus)? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="Khusus" value="0"{{'0'== old('Khusus',$msupplier->Khusus)? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>

                       <button class="btn btn-primary">Save</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
