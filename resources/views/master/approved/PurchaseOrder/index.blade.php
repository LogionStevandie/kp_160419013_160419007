
@extends('layouts.home_master')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@section('judul')
Persetujuan Order
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Persetujuan Order</li>
<li class="breadcrumb-item active">Purchase Order</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permintaan Purchase Order</h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col">Status Approve</th>
                                <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($poKeluar != null)
                            @foreach($poKeluar as $purchaseOrder)
                            <tr>
                                <th scope="row" name='id'>{{$purchaseOrder->id}}</th>
                                <td>{{$purchaseOrder->name}}</td>
                                <td id="dengan-rupiah">{{$purchaseOrder->totalHarga}}</td>
                                <td>{{date("d-m-Y", strtotime($purchaseOrder->tanggalDibuat))}}</td>
                                @if($purchaseOrder->approved==0)
                                <td>Pending</td>
                                @elseif($purchaseOrder->approved==1)
                                <td>Approved</td>
                                @elseif($purchaseOrder->approved==2)
                                <td>Not Approved</td>
                                @endif
                                <td>  
                                <a href="{{route('approvedPurchaseOrder.edit',[$purchaseOrder->id])}}" class="btn btn-primary btn-responsive">Approve</a>  
                                </td> 
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                    <th scope="col">Status Approve</th>
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

<script>
    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('dengan-rupiah');
    dengan_rupiah.addEventListener('keyup', function(e)
    {
        //$('#hargaBarang').val(this.value.toString().replace(/\./g, ''));
        dengan_rupiah.value = formatRupiah(parseInt(this.value), 'Rp. ');
    });

    /* Fungsi */
    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah     = split[0].substr(0, sisa),
            ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection
