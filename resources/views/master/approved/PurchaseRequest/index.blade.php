
@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Persetujuan Pembelian
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Persetujuan Pembelian</li>
<li class="breadcrumb-item active">Permintaan Pembelian</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permintaan Pembelian</h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status Approve 1</th>
                                <th scope="col">Status Approve 2</th>
                                <th scope="col">Handle</th>
                                </tr>
                            </thead>
                              <tbody>
                                @foreach($prKeluar as $purchaseRequest)
                                <tr >
                                <th scope="row" name='id'>{{$purchaseRequest->id}}</th>
                                <td>{{$purchaseRequest->name}}</td>
                                @if($purchaseRequest->approved==0)
                                <td>Pending</td>
                                @elseif($purchaseRequest->approved==1)
                                <td>Approved</td>
                                @elseif($purchaseRequest->approved==2)
                                <td>Not Approved</td>
                                @endif

                                @if($purchaseRequest->approvedAkhir==0)
                                <td>Pending</td>
                                @elseif($purchaseRequest->approvedAkhir==1)
                                <td>Approved</td>
                                @elseif($purchaseRequest->approvedAkhir==2)
                                <td>Not Approved</td>
                                @endif
                                <td>  
                                <a href="{{route('approvedPurchaseRequest.edit',[$purchaseRequest->id])}}" class="btn btn-primary btn-responsive">Approve</a>
                                    
                                
                                </td>
                                
                                </tr>
                                @endforeach
                            
                            </tbody>
                        <tfoot>
                             <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status Approve 1</th>
                                <th scope="col">Status Approve 2</th>
                                <th scope="col">Handle</th>
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

@endsection
