
@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Supplier
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
                            <label for="title">Mcurrency</label>
                            <select name="mCurrencyID" class="form-control select2" disabled>
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
                            <label for="title">Payment Terms</label>
                            <select name="PaymentTermsID"class="form-control select2" disabled>
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
                            <label for="title">Nama Supplier</label>
                           <input require type="text" name="name" class="form-control" 
                           value="{{old('Name',$msupplier->Name)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Alamat Supplier</label>
                           <input require type="text" name="alamat" class="form-control" 
                           value="{{old('Alamat',$msupplier->Alamat)}}" disabled>
                        </div>

                         <div class="form-group">
                            <label for="title">Kota</label>
                            <input require type="text" name="kota" class="form-control" 
                           value="{{old('Kota',$msupplier->Kota)}}" disabled>
                        </div>
                     
                        

                        <div class="form-group">
                            <label for="title">Kode Pos</label>
                            <input require type="text" name="kodePos" class="form-control" 
                           value="{{old('KodePos',$msupplier->KodePos)}}" disabled>
                        </div>


                        <div class="form-group">
                            <label for="title">Phone 1</label>
                            <input require type="text" name="phone1" class="form-control" 
                           value="{{old('Phone1',$msupplier->Phone1)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Phone 2</label>
                            <input require type="text" name="phone2" class="form-control" 
                           value="{{old('Phone2',$msupplier->Phone2)}}" disabled>
                        </div>

                        
                        <div class="form-group">
                            <label for="title">Fax 1</label>
                            <input require type="text" name="fax1" class="form-control" 
                           value="{{old('Fax1',$msupplier->Fax1)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Fax 2</label>
                            <input require type="text" name="fax2" class="form-control" 
                           value="{{old('Fax2',$msupplier->Fax2)}}" disabled>
                        </div>

                         <div class="form-group">
                            <label for="title">Contact Person</label>
                            <input require type="text" name="contactPerson" class="form-control" 
                           value="{{old('ContactPerson',$msupplier->ContactPerson)}}" disabled>
                        </div>

                         <div class="form-group">
                            <label for="title">email</label>
                            <input require type="text" name="email" class="form-control" 
                           value="{{old('Email',$msupplier->Email)}}" disabled>
                        </div>

                         <div class="form-group">
                            <label for="title">NPWP</label>
                            <input require type="text" name="NPWP" class="form-control" 
                           value="{{old('NPWP',$msupplier->NPWP)}}" disabled>
                        </div>

                         <div class="form-group">
                            <label for="title">Rekening Bank</label>
                            <input require type="text" name="rekeningBank" class="form-control" 
                           value="{{old('RekeningBank',$msupplier->RekeningBank)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Bank</label>
                             <select class="form-control select2" style="width: 100%;" name="COAID" disabled>
                                    <option value="">--Pilih Bank--</option>
                                    @foreach($bank as $key => $data)
                                    @if($data->id==$msupplier->BankID)
                                    <option selected value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                                    @else
                                    <option value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                                    @endif
                                    @endforeach
                            </select>

                        </div>

                        

                         <div class="form-group">
                            <label for="title">No Rekening</label>
                            <input require type="text" name="noRekening" class="form-control" 
                           value="{{old('NoRekening',$msupplier->NoRekening)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">note</label>
                            <input require type="text" name="note" class="form-control" 
                           value="{{old('Note',$msupplier->Note)}}" disabled>
                        </div>

                           <div class="form-group">
                            <label for="title">Atas Nama</label>
                            <input require type="text" name="atasNama" class="form-control" 
                           value="{{old('AtasNama',$msupplier->AtasNama)}}" disabled>
                        </div>

                           <div class="form-group">
                            <label for="title">Lokasi</label>
                            <input require type="text" name="lokasi" class="form-control" 
                           value="{{old('Lokasi',$msupplier->Lokasi)}}" disabled>
                        </div>

                           <div class="form-group">
                            <label for="title">Kode</label>
                            <input require type="text" name="kode" class="form-control" 
                           value="{{old('Kode',$msupplier->Kode)}}" disabled>
                        </div>

                          <div class="form-group">
                            <label for="title">Keterangan</label>
                            <input require type="text" name="keterangan" class="form-control" 
                           value="{{old('Keterangan',$msupplier->Keterangan)}}" disabled>
                        </div>

                        

                          <div class="form-group">
                            <label for="title">Nama NPWP</label>
                            <input require type="text" name="namaNPWP" class="form-control" 
                           value="{{old('NamaNPWP',$msupplier->NamaNPWP)}}"disabled >
                        </div>


                        <div class="form-group">
                            <label for="title">KTP</label>
                            <input require type="text" name="KTP" class="form-control" 
                           value="{{old('KTP',$msupplier->KTP)}}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="title">Mkota</label>
                            <select class="form-control select2" style="width: 100%;" name="mKotaID" disabled>
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
                                <input disabled class="form-check-input" type="radio" name="Petani" value="1"{{'1' == old('Petani',$msupplier->Petani)? 'checked' :'' }} >
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input disabled class="form-check-input" type="radio" name="Petani" value="0"{{'0'== old('Petani',$msupplier->Petani)? 'checked' :'' }} >
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>
                       
        
                        <div class="form-group">
                            <label for="title">Khusus</label><br>
                            <div class="form-check form-check-inline">
                                <input disabled class="form-check-input" type="radio" name="Khusus" value="1"{{'1' == old('Khusus',$msupplier->Khusus)? 'checked' :'' }} >
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input disabled class="form-check-input" type="radio" name="Khusus" value="0"{{'0'== old('Khusus',$msupplier->Khusus)? 'checked' :'' }} >
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
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

