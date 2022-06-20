
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
<li class="breadcrumb-item">Persetujuan </li>
<li class="breadcrumb-item"><a href="{{route('approvedPurchaseRequest.index')}}">Permintaan Pembelian</a></li>
<li class="breadcrumb-item active">Approve</li>
@endsection

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form method="POST" action="{{route('approvedPurchaseRequest.update',[$purchaseRequest->id])}}" >
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col" colspan="3"><h2>PERSETUJUAN PERMINTAAN PEMBELIAN</h2></th>
                                        <th scope="col" colspan="3">
                                            Nama Npp : {{$purchaseRequest->name}}<br>
                                            Tanggal pembuatan : {{date("d-m-Y", strtotime($purchaseRequest->tanggalDibuat))}}

                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col"colspan="6"cellspacing="3" >
                                                                            
                                        @foreach($dataGudang as $data)
                                            @if($data->MGudangID == $purchaseRequest->MGudangID)
                                            Gudang :{{$data->cname}} <nbsp> ({{$purchaseRequest->MGudangID}}) <br> 
                                            @endif 
                                        @endforeach
                                        Jenis permintaan : {{$purchaseRequest->jenisProses}} <br>
                                        Tanggal dibutuhkan : {{date("d-m-Y", strtotime($purchaseRequest->tanggalDibutuhkan))}}<br>
                                        Tanggal batas akhir : {{date("d-m-Y", strtotime($purchaseRequest->tanggalAkhirDibutuhkan))}}
                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                            @foreach($prd as $data) 
                                            <tr>
                                                @if($data->idPurchaseRequest==$purchaseRequest->id)
                                                <td>{{ $loop->index + 1 }}</td>
                                                <th scope="row">{{$data->ItemName}}</th>
                                                <td>{{$data->jumlah}}</td>
                                                <td class="text-right" >@php echo "Rp " . number_format($data->harga,2,',','.'); @endphp </td>              
                                                <td> <span style="white-space: pre-line">{{$data->keterangan_jasa}}</span></td>                                          
                                                <td class="text-right" > @php echo "Rp " . number_format($data->jumlah * $data->harga,2,',','.'); @endphp </td>                                          
                                                @endif
                                            </tr>
                                            @endforeach
                                    
                                    </tbody>
                                     
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" colspan="5"> <h3>Total</h3> </th>
                                            <th scope="col" colspan="5" class="text-right" > @php echo "Rp " . number_format($purchaseRequest->totalHarga,2,',','.'); @endphp </th>
                                        
                                        </tr>
                                    </thead>
                                    
                                </table>

                            </div>
                        
                            <div class="form-group">
                                <label for="title">Pembelian / Penjualan</label><br>
                                <div class="icheck-primary d-inline">
                                    <input id="setuju"  type="radio" name="approve" value="1"{{'1' == old('approved','')? 'checked' :'' }}>
                                    <label class="form-check-label" for="setuju">Setuju</label>
                                </div>
                                <div class="icheck-primary d-inline">
                                    <input id="tdkSetuju"  type="radio" name="approve" value="2"{{'2'== old('approved','')? 'checked' :'' }}>
                                    <label class="form-check-label" for="tdkSetuju">Tidak Setuju</label><br>
                                </div><br><br>
                                <div id="ket">
                                    <label class="form-check-label" >Keterangan :</label><br>
                                    <textarea rows="3" required type="text" name="keterangan"class="form-control" value="{{old('keterangan','')}}" id="txt"></textarea>
                                </div>
                            </div>    

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$('body').on("click", "#setuju", function (){

         $("#ket").hide();
         document.getElementById("txt").value = "-";
});
$('body').on("click", "#tdkSetuju", function (){

        $("#ket").show();
});

$('#hargaTotal').html("Rp." +formatRupiah($('#hargaTotal').attr('hargaT')));
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
