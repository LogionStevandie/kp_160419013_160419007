
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
<li class="breadcrumb-item"><a href="{{route('approvedPurchaseOrder.index')}}">Purchase Order</a></li>
<li class="breadcrumb-item active">Approve</li>
@endsection

@section('content')

<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form method="POST" action="{{route('approvedPurchaseOrder.update',[$purchaseOrder->id])}}" >
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col" colspan="4"><h2>PERSETUJUAN PURCHASE ORDER</h2></th>
                                    <th scope="col" colspan="4">
                                        Nama PO : {{$purchaseOrder->name}}<br>
                                        Tanggal pembuatan : {{date("d-m-Y", strtotime($purchaseOrder->tanggalDibuat))}}
                                    </th>
                                    </tr>
                                </thead>
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"colspan="8"cellspacing="3" >
                                                                        
                                    @foreach($dataPerusahaan as $data)
                                        @if($data->MPerusahaanID == $purchaseOrder->MPerusahaanID)
                                        Gudang :{{$data->cname}} <nbsp> ({{$purchaseOrder->MPerusahaanID}}) <br> 
                                        @endif 
                                    @endforeach

                                    @foreach($dataSupplier as $data)
                                        @if($data->SupplierID == $purchaseOrder->idSupplier)
                                        Supplier :{{$data->Name}}  <br> 
                                        @endif 
                                    @endforeach
                                    Jenis permintaan : {{date("d-m-Y", strtotime($purchaseOrder->jenisProses))}} <br>
                                    Tanggal batas akhir : {{date("d-m-Y", strtotime($purchaseOrder->tanggal_akhir))}}
                                    </th>
                                    </tr>
                                </thead>
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Diskon</th>
                                        <th scope="col">Pajak</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Total Harga</th> <!--ni ws tak bantu gae colomn atas e doang:v-->
                                    </tr>
                                </thead>
                                <tbody>

                                        @foreach($pod as $data) 
                                        <tr>
                                            @if($data->idPurchaseOrder==$purchaseOrder->id)
                                            <td>{{ $loop->index + 1 }}</td>
                                            <th scope="row">{{$data->namaItem}}</th>
                                            <td>{{$data->jumlah}}</td>
                                            <td class="text-right">  @php echo "Rp " . number_format($data->harga,2,',','.'); @endphp   </td>  
                                          
                                            <td>{{$data->diskon}}</td> 
                                            <td>{{$data->namaTax}}</td>    <!--nntk loopng-->      
                                            <td> <span style="white-space: pre-line">{{$data->keterangan}}</span></td>  

                                            <td class="text-right"> @php echo "Rp " . number_format($data->jumlah * $data->harga,2,',','.'); @endphp   </td>                                          
                                            @endif
                                        </tr>
                                        @endforeach
                                
                                </tbody>
                                 <thead class="thead-light justify-content-center">
                                    <tr>
                                        <th scope="col" colspan="7" > <h3>Total</h3> </th>
                                        <th scope="col" colspan="7" class="text-right" > <h3>@php echo "Rp " . number_format($purchaseOrder->totalHarga,2,',','.'); @endphp</h3> </th>
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
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>


<script type="text/javascript">
$('body').on("click", "#setuju", function (){

         $("#ket").hide();
         document.getElementById("txt").value = "-";
});
$('body').on("click", "#tdkSetuju", function (){

        $("#ket").show();
});

</script>
@endsection
