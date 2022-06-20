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
<li class="breadcrumb-item">Persetujuan</li>
<li class="breadcrumb-item"><a href="{{route('approvedPurchaseOrder.index')}}">Purchase Order</a></li>
<li class="breadcrumb-item active">Approve</li>
@endsection

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form method="POST" action="{{route('approvedPurchaseOrder.update',[$purchaseOrder->id])}}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" colspan="3">
                                    <h2> <img src="" alt="">PURCHASE ORDER</h2>
                                </th>
                                <th scope="col" colspan="3">
                                    <b>PO : {{$purchaseOrder->name}}</b><br>
                                    <b>Tanggal pembuatan :{{date("d-m-Y", strtotime($purchaseOrder->tanggalDibuat))}}</b>



                                </th>
                                <th scope="col" colspan="1">
                                    @foreach($dataPerusahaan as $key => $data)

                                    @if($data->MPerusahaanID == $purchaseOrder->MPerusahaanID)
                                    @if($data->Gambar !="" || $data->Gambar !=null)
                                    <img src='{{asset($data->Gambar)}}' alt='' width='50' height='50'>
                                    @endif
                                    @endif

                                    @endforeach
                                </th>
                            </tr>
                        </thead>
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" colspan="3">
                                    <b>Keterangan:</b>
                                    <br>
                                    <b>Lokasi:</b><span style="white-space: pre-line">{{$purchaseOrder->keteranganLokasi}}</span> <br>
                                    <b>Pembayaran:</b><span style="white-space: pre-line">{{$purchaseOrder->keteranganPembayaran}}</span><br>
                                    <b>Penagihan:</b><span style="white-space: pre-line">{{$purchaseOrder->keteranganPenagihan}}</span><br>
                                </th>
                                <th scope="col" colspan="4" style="vertical-align: top;">
                                    <b>Jatuh Tempo:</b> {{date("d-m-Y", strtotime($purchaseOrder->tanggal_akhir))}}<br>

                                    @foreach($dataPerusahaan as $key => $data)

                                    @if($data->MPerusahaanID == $purchaseOrder->MPerusahaanID)
                                    <b>Perusahaan:</b> {{$data->cname}}<br>
                                    <b>Nomor NPWP:</b> {{$data->NomorNPWP}}<br>
                                    <b>Alamat NPWP:</b> {{$data->AlamatNPWP}} <br>
                                    @endif

                                    @endforeach

                                    @foreach($dataSupplier as $key => $data)
                                    @if($data->SupplierID == $purchaseOrder->idSupplier)
                                    <b>Supplier:</b> {{$data->Name}}<br>
                                    @endif
                                    @endforeach

                                    @foreach($dataPayment as $key => $data)
                                    @if($data->PaymentTermsID == $purchaseOrder->idPaymentTerms)
                                    <b>Pembayaran:</b>{{$data->PaymentName}}<br>
                                    @endif
                                    @endforeach
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>
                <!-- /.col -->
            </div>
            <div class="card-body">
                <div class="form-group">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Pajak</th>
                                <th>Diskon</th>
                                <th>Harga Diskon</th>
                                <th>PPN</th>
                                <th>Harga PPN</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalHarga = 0;
                            $totalHargaDiskon = 0;
                            $totalHargaPPN = 0;
                            @endphp
                            @foreach($dataDetail as $data)
                            <tr>
                                @if($data->idPurchaseOrder==$purchaseOrder->id)
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                @foreach($dataBarang as $dataBrg)
                                @if($data->idItem==$dataBrg->ItemID)
                                <th scope="row">
                                    {{$dataBrg->ItemName}}<br>
                                    <small><span style="white-space: pre-line">{{$data->keterangan}}</span></small>
                                </th>
                                <!--nanti looping -->
                                @endif
                                @endforeach

                                <td>{{$data->jumlah}}</td>
                                <td class="text-right">@php echo "Rp " . number_format($data->harga,2,',','.'); @endphp</td>

                                @foreach($dataTax as $dataTx)
                                @if($data->idTax==$dataTx->TaxID)
                                <td>{{$dataTx->Name}}</td>
                                @endif
                                @endforeach


                                <td class="text-right">@php echo "Rp " . number_format($data->diskon,2,',','.'); @endphp</td>
                                <!--<td class="text-right">@php echo "Rp " . number_format((((float)$data->harga- (float)$data->diskon) * $data->jumlah),2,',','.'); @endphp</td>-->
                                <td class="text-right">@php echo "Rp " . number_format((((float)$data->diskon) * $data->jumlah),2,',','.'); @endphp</td>
                                <td class="text-right">{{$data->TaxPercent}} %</td>
                                <td class="text-right">@php echo "Rp " . number_format((((float)$data->harga- (float)$data->diskon) * $data->jumlah) * ((float)$data->TaxPercent) / 100.0,2,',','.'); @endphp</td>
                                <td class="text-right">@php echo "Rp " . number_format((((float)$data->harga- (float)$data->diskon) * $data->jumlah) * (100.0 + (float)$data->TaxPercent) / 100.0,2,',','.'); @endphp</td>
                                @php
                                $totalHarga += (float)$data->harga * $data->jumlah;
                                $totalHargaDiskon += $data->diskon * $data->jumlah;
                                $totalHargaPPN += (((float)$data->harga- (float)$data->diskon) * $data->jumlah) * (float)$data->TaxPercent / 100.0;
                                @endphp

                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        <thead class="thead-light justify-content-center">
                            <tr>
                                <th scope="col" colspan="8"></th>
                                <th scope="col" colspan="1"> Total Harga</th>
                                <th scope="col" colspan="10" class="text-right"> @php echo "Rp " . number_format($totalHarga,2,',','.'); @endphp </th>
                            </tr>
                            <tr>
                                <th scope="col" colspan="8"></th>
                                <th scope="col" colspan="1"> Total Harga Diskon </th>
                                <th scope="col" colspan="10" class="text-right"> @php echo "Rp " . number_format($totalHargaDiskon,2,',','.'); @endphp </th>
                            </tr>
                            <tr>
                                <th scope="col" colspan="8"></th>
                                <th scope="col" colspan="1"> Total Harga PPN </th>
                                <th scope="col" colspan="10" class="text-right"> @php echo "Rp " . number_format($totalHargaPPN,2,',','.'); @endphp </th>
                            </tr>
                            <tr>
                                <th scope="col" colspan="8"></th>
                                <th scope="col" colspan="1"> Total Keseluruhan </th>
                                <th scope="col" colspan="10" class="text-right"> @php echo "Rp " . number_format($purchaseOrder->totalHarga,2,',','.'); @endphp </th>
                            </tr>
                        </thead>


                    </table>


                </div>
                <div class="form-group">
                    <label for="title">Pembelian / Penjualan</label><br>
                    <div class="icheck-primary d-inline">
                        <input id="setuju" type="radio" name="approve" value="1" {{'1' == old('approved','')? 'checked' :'' }}>
                        <label class="form-check-label" for="setuju">Setuju</label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input id="tdkSetuju" type="radio" name="approve" value="2" {{'2'== old('approved','')? 'checked' :'' }}>
                        <label class="form-check-label" for="tdkSetuju">Tidak Setuju</label><br>
                    </div><br><br>
                    <div id="ket">
                        <label class="form-check-label">Keterangan :</label><br>
                        <textarea rows="3" required type="text" name="keterangan" class="form-control" value="{{old('keterangan','')}}" id="txt"></textarea>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Kirim</button>
            </div>
        </form>
    </div>


    <script type="text/javascript">
        $('body').on("click", "#setuju", function() {

            $("#ket").hide();
            document.getElementById("txt").value = "-";
        });
        $('body').on("click", "#tdkSetuju", function() {

            $("#ket").show();
        });
    </script>
    @endsection