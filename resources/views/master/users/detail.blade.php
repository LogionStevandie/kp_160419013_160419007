@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Tambah Users
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{route('users.show', [$userss->id])}}" method="POST">
            @csrf
            @method("PUT")
            <div class="card-body">

                <div class="form-group">
                    <label for="title">Nama</label>
                    <input disabled required type="text" name="name" maxlength="255" class="form-control" value="{{old('name',$userss->name)}}">
                </div>
                <div class="form-group">
                    <label for="title">NIK</label>
                    <input disabled required type="text" name="email" maxlength="12" class="form-control" value="{{old('email',$userss->email)}}">
                </div>
                <div class="form-group">
                    <label for="title">Password</label>
                    <input disabled placeholder="Optional, Jika ingin mengubah password" type="password" name="password" maxlength="100" class="form-control" value="{{old('password','')}}">
                </div>

                <div class="form-group">
                    <label for="lastName">Pilih Role</label>
                    <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="role">
                        <option value="">
                            --Pilih Role--
                        </option>
                        @foreach($dataRole as $key => $data)
                        @if($data->id == $userss->idRole)
                        <option selected name="idRole" value="{{$data->id}}" {{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @else
                        <option name="idRole" value="{{$data->id}}" {{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                        @endif
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label for="lastName">Pilih Gudang</label>
                    <select disabled class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangID">
                        <option value="">
                            --Pilih Gudang--
                        </option>
                        @foreach($dataGudang as $key => $data)
                        @if($data->MGudangID == $userss->MGudangID)
                        <option selected name="idGudang" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @else
                        <option name="idGudang" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endif
                        @endforeach

                    </select>
                </div>

                @foreach($userdata as $user)
                <div class="form-group ">
                    <label for="active">Kepala Divisi Gudang</label><br>
                    @if($user->UserIDKepalaDivisi == $user->id)
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDKepalaDivisi" value="1" {{'1' == old('UserIDKepalaDivisi',$userss->UserIDKepalaDivisi)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDKepalaDivisi" value="0" {{'0'== old('UserIDKepalaDivisi',$userss->UserIDKepalaDivisi)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @else
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDKepalaDivisi" value="1" {{'1' == old('UserIDKepalaDivisi',$userss->UserIDKepalaDivisi)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDKepalaDivisi" value="0" {{'0'== old('UserIDKepalaDivisi',$userss->UserIDKepalaDivisi)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @endif
                    <br>
                </div>
                @endforeach

                <div class="form-group">
                    <label for="title">Perusahaan</label>
                    <input readonly type="text" id="perusahaan" maxlength="255" class="form-control" value="{{old('name','')}}">
                </div>
                @foreach($userdata as $user)

                <div class="form-group ">
                    <label for="active">Manager Perusahaan 1</label><br>
                    @if($user->UserIDManager1 == $user->id)
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDManager1" value="1" {{'1' == old('UserIDManager1',$userss->UserIDManager1)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDManager1" value="0" {{'0'== old('UserIDManager1',$userss->UserIDManager1)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @else
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDManager1" value="1" {{'1' == old('UserIDManager1',$userss->UserIDManager1)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDManager1" value="0" {{'0'== old('UserIDManager1',$userss->UserIDManager1)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @endif
                    <br>
                </div>

                <div class="form-group ">
                    <label for="active">Manager Perusahaan 2</label><br>
                    @if($user->UserIDManager2 == $user->id)
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDManager2" value="1" {{'1' == old('UserIDManager2',$userss->UserIDManager2)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDManager2" value="0" {{'0'== old('UserIDManager2',$userss->UserIDManager2)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @else
                    <div class="form-check form-check-inline">
                        <input disabled class="form-check-input" type="radio" name="UserIDManager2" value="1" {{'1' == old('UserIDManager2',$userss->UserIDManager2)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input disabled checked class="form-check-input" type="radio" name="UserIDManager2" value="0" {{'0'== old('UserIDManager2',$userss->UserIDManager2)? 'checked' :'' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @endif
                    <br>
                </div>
                @endforeach
            </div>
            <!-- /.card-body -->
            <div class="card-footer">

                <a class="btn btn-primary" href="{{route('users.index')}}">Kembali</a>

            </div>
        </form>
    </div>


    <script>
        $(document).ready(function() {

            $("#idGudang").on("change", function() { //sudah

                var id = this.value;
                var dataGudang = <?php echo json_encode($dataGudang); ?>;
                $.each(dataGudang, function(key, value) {
                    if (value.MGudangID.toString() == id.toString()) {
                        $("#perusahaan").val(value.perusahaanName);
                        $("#perusahaan").html(value.perusahaanName);
                    }
                });
            });
        });
    </script>
    @endsection