@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Tambah item
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('item.index')}}">item</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('item.store')}}" method="POST" >
        @csrf
        <div class="card-body">
              <div class="form-group ">
                             <label for="title">Tipe item</label>
                            <select name="typeItem" class="form-control">
                                    <option value="">--Pilih Tipe Item--</option>
                                    @foreach($dataType as $key => $data)
                                    <option  value="{{$data->ItemTypeID}}"{{$data->Name == $data->ItemTypeID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>
                        </div>
                     

                        <div class="form-group">
                            <label for="title">Nama Item</label>
                           <input require type="text" name="nameItem" class="form-control" 
                           value="{{old('nameItem','')}}" maxlength="200">
                        </div>

                        <div class="form-group">
                           <label for="title">Unit item</label>
                            <select name="itemUnit" class="form-control">
                                    <option value="">--Pilih Unit Item--</option>
                                    @foreach($dataUnit as $key => $data)
                                    <option value="{{$data->UnitID}}"{{$data->Name == $data->UnitID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>

                        </div>
                        
                        <div class="form-group">
                            <label for="title">Kategori Item</label>
                            <select name="itemCategory" class="form-control">
                                    <option value="">--Pilih Kategori Item--</option>
                                    @foreach($dataCategory as $key => $data)
                                    <option value="{{$data->ItemCategoryID}}"{{$data->Name == $data->ItemCategoryID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>
                        </div>

                       <div class="form-group">
                            <label for="title">Keterangan</label>
                            <input require type="text" name="note" class="form-control" 
                           value="{{old('note','')}}" maxlength="256">
                        </div>

                           <div class="form-group " >
                            <label for="active">Bisa dibeli</label><br>
                            <div class="form-check form-check-inline">
                                <input  checked class="form-check-input" type="radio" name="CanBePurchased" value="1"{{'1' == old('CanBePurchased','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input  class="form-check-input" type="radio" name="CanBePurchased" value="0"{{'0'== old('CanBePurchased','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>

                         <div class="form-group ">
                            <label for="active">Bisa dijual</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="CanBeSell" value="1"{{'1' == old('CanBeSell','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked  class="form-check-input" type="radio" name="CanBeSell" value="0"{{'0'== old('CanBeSell','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>

                         <div class="form-group ">
                            <label for="title">Item Tracing</label>
                            <select name="itemTracing" class="form-control">
                                    <option value="">--Pilih Tracing Item--</option>
                                    @foreach($dataTracing as $key => $data)
                                    <option value="{{$data->ItemTracingID}}"{{$data->Name == $data->ItemTracingID? 'selected' :'' }}>{{$data->Name}}</option>
                                    @endforeach
                            </select>
                        </div>

                      
                         <div class="form-group ">
                            <label for="active">Memiliki tanggal kadaluarsa</label><br>
                            <div class="form-check form-check-inline">
                                <input checked class="form-check-input" type="radio" name="expiredDate" value="1"{{'1' == old('HaveExpiredDate','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="expiredDate" value="0"{{'0'== old('HaveExpiredDate','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>
                       
        
                        <div class="form-group ">
                            <label for="active">Untuk Diproduksi</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RoutesToManufactured" value="1"{{'1' == old('RoutesToManufactured','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked class="form-check-input" type="radio" name="RoutesToManufactured" value="0"{{'0'== old('RoutesToManufactured','')? 'checked' :'' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div><br>
                        </div>



        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection

