@extends('layouts.home_master')

@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

@section('judul')
Satuan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="container-fluid">
    <h4 class="text-center display-4">Cari User</h4>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="/userse/searchname/" method="get">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" name="searchname" placeholder="Nama user">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="/userse/searchgudang/" method="get">
                <div class="input-group">
                    <select class="form-control selectpicker col-md-8" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudangTujuan" name="searchgudang">
                        <option value="">
                            --Semua Gudang--
                        </option>
                        @foreach($dataGudang as $data)
                        <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endforeach

                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="/userse/searchperusahaan/" method="get">
                <div class="input-group">
                    <select class="form-control selectpicker col-md-8" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudangTujuan" name="searchperusahaan">
                        <option value="">
                            --Semua Gudang--
                        </option>
                        @foreach($dataPerusahaan as $data)
                        <option name="idPerusahaan" value="{{$data->MPerusahaanID}}" {{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endforeach

                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                    <a href="{{route('users.create')}}" class="btn btn-primary btn-responsive float-right">Tambah User
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                        </svg>
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Role</th>
                                <th>Gudang</th>
                                <th>Kepala Divisi Gudang</th>
                                <th>Manager Perusahaan 1</th>
                                <th>Manager Perusahaan 2</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataaa as $user)
                            <tr>
                                <th>{{$user->id}}</th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->roleName}}</td>
                                <td>{{$user->gudangName}}</td>
                                <td>
                                    @if($user->UserIDKepalaDivisi == $user->id)
                                    {{$user->gudangName}}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($user->UserIDManager1 == $user->id)
                                    {{$user->perusahaanName}}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if($user->UserIDManager2 == $user->id)
                                    {{$user->perusahaanName}}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-default bg-info" href="{{route('users.show',[$user->id])}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-default bg-info" href="{{route('users.edit',[$user->id])}}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-default bg-danger" data-toggle="modal" data-target="#delete_{{$user->id}}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="delete_{{$user->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h4 class="modal-title">Konfirmasi</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    Apakah anda yakin mau menghapus "{{$user->name}}"
                                                </div>

                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                                                    <form action="{{route('users.destroy',[$user->id])}}" method="POST" class="btn btn-responsive">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-default bg-danger" action="{{route('users.destroy',[$user->id])}}">
                                                            Hapus
                                                        </button>
                                                        @csrf
                                                    </form>
                                                </div>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Role</th>
                                <th>Gudang</th>
                                <th>Kepala Divisi Gudang</th>
                                <th>Manager Perusahaan 1</th>
                                <th>Manager Perusahaan 2</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
{{ $dataaa->links('pagination::bootstrap-4') }}
@endsection