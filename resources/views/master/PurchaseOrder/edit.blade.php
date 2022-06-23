@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Ubah Nota Purchase Order
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Nota</li>
<li class="breadcrumb-item"><a href="{{route('purchaseOrder.index')}}">Nota Purchase Order</a></li>
<li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<form action="{{route('purchaseOrder.update', [$purchaseOrder->id])}}" method="POST">
    @csrf
    @method('PUT')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!--<div class="callout callout-info">
              <h2>Pembuatan Nota Permintaan Pembelian</h2><br>
            </div>


             Main content -->
                    <div class="invoice p-2 mb-3">

                        <!-- /.row -->

                        <!-- isi row -->
                        <div class="row">
                            <div class="col-12 ">
                                <div class="py-5 ">

                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label for="firstName">Nama Purchase Order</label>
                                            <input type="text" class="form-control" id="firstName" placeholder="" value="{{old('name',$purchaseOrder->name)}}" readonly required="">
                                            <div class="invalid-feedback"> Valid first name is required. </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Tanggal Pembuatan</label>
                                            <input readonly name="tanggalDibuat" type="date" class="form-control" id="lastName" placeholder="" value="{{old('created_on',$purchaseOrder->tanggalDibuat)}}" required="">
                                            <div class="invalid-feedback"> Valid last name is required. </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Pilih Perusahaan</label>
                                                <select readonly class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="perusahaanID" name="perusahaan">
                                                    <option value="">
                                                        --Pilih Perusahaan--
                                                    </option>

                                                    @foreach($dataPerusahaan as $key => $data)

                                                    @if($data->MPerusahaanID == $purchaseOrder->MPerusahaanID)
                                                    <option selected name="idPerusahaan" singkatan="{{$data->cnames}}" value="{{$data->MPerusahaanID}}" {{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}} ({{$data->cnames}})</option>
                                                    @else
                                                    <option name="idPerusahaan" singkatan="{{$data->cnames}}" value="{{$data->MPerusahaanID}}" {{$data->cname == $data->MPerusahaanID? 'selected' :'' }}>{{$data->cname}} ({{$data->cnames}})</option>
                                                    @endif

                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Tanggal Batas Akhir</label>
                                            <input type="date" name="tanggal_akhir" class="form-control" id="lastName" placeholder="" value="{{old('tanggal_akhir',$purchaseOrder->tanggal_akhir)}}" required="">
                                            <div class="invalid-feedback"> Valid last name is required. </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Cara Pembayaran</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="paymentTerms">
                                                    <option value="">
                                                        --Pilih Cara Pembayaran--
                                                    </option>
                                                    @foreach($dataPayment as $key => $data)
                                                    @if($data->PaymentTermsID == $purchaseOrder->idPaymentTerms)
                                                    <option selected name="idPaymentTerms" value="{{$data->PaymentTermsID}}" {{$data->Name == $data->PaymentTermsID? 'selected' :'' }}>{{$data->Name}} ({{$data->PaymentName}})</option>
                                                    @else
                                                    <option name="idPaymentTerms" value="{{$data->PaymentTermsID}}" {{$data->Name == $data->PaymentTermsID? 'selected' :'' }}>{{$data->Name}} ({{$data->PaymentName}})</option>
                                                    @endif
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>




                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="lastName">Supplier</label>
                                                <select name="supplier" class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;">
                                                    <option value="">
                                                        --Pilih Supplier--
                                                    </option>
                                                    @foreach($dataSupplier as $key => $data)
                                                    @if($data->SupplierID == $purchaseOrder->idSupplier)
                                                    <option selected name="idSupplier" value="{{$data->SupplierID}}" {{$data->Name == $data->SupplierID? 'selected' :'' }}>{{$data->Name}}</option>
                                                    @else
                                                    <option name="idSupplier" value="{{$data->SupplierID}}" {{$data->Name == $data->SupplierID? 'selected' :'' }}>{{$data->Name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Lokasi</label>
                                            <textarea rows="3" type="text" name="keteranganLokasi" class="form-control" value="{{old('keteranganLokasi',$purchaseOrder->keteranganLokasi)}}">{{$purchaseOrder->keteranganLokasi}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Pembayaran</label>
                                            <textarea rows="3" type="text" name="keteranganPembayaran" class="form-control" value="{{old('keteranganPembayaran',$purchaseOrder->keteranganPembayaran)}}">{{$purchaseOrder->keteranganPembayaran}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Penagihan</label>
                                            <textarea rows="3" type="text" name="keteranganPenagihan" class="form-control" value="{{old('keteranganPenagihan',$purchaseOrder->keteranganPenagihan)}}">{{$purchaseOrder->keteranganPenagihan}}</textarea>
                                        </div>

                                    </div>
                                </div>
                                <!-- Page Heading -->
                                <div class="card card-primary">
                                    <!-- form start -->



                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="card card-danger">
                                                <div class="card-header">
                                                    <h3 class="card-title">Pemilihan Barang</h3>
                                                </div>
                                                <div class="card-body">
                                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="selectPRD" id="pReq">
                                                        <option value="pilih">--Permintaan Order--</option>
                                                        <!--
                                           @foreach($dataPurchaseRequest as $key => $data)
                                              @if($data->id == $purchaseOrder->id)
                                                <option selected id="preqID" value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>
                                              @else
                                                <option id="preqID" value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}</option>  
                                              @endif
                                           
                                            @endforeach-->
                                                    </select><br>


                                                    <div class="form-group" id='tmbhBarang'>
                                                        <label for="title">Barang</label>

                                                        <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="barang" id="barang">

                                                            <option value="pilih">--Pilih barang--</option>
                                                            <!--@foreach($dataBarang as $key => $data)
                                        <option id="namaBarang" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})</option>
                                        @endforeach-->
                                                        </select>
                                                        <input id="jumlahBarang" value="1" min="1" max="2" type="number" step=".01" class="form-control" placeholder="Jumlah barang" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                                    </div>
                                                    <div class="form-group" id="harga">
                                                        <label for="Harga">Harga (Rupiah)</label>
                                                        <input type="text" step=".01" id="tanpa-rupiah" class="form-control" value="{{old('harga','')}}">
                                                        <input type="hidden" id="hargaBarang" value="">
                                                    </div>

                                                    <div class="form-group" id='taxDiv'>
                                                        <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="tax" id="tax">
                                                            <option value="">--Pajak--</option>
                                                            @foreach($dataTax as $key => $data)
                                                            <option id="taxId" taxPercent={{$data->TaxPercent}} value="{{$data->TaxID}}" {{$data->Name == $data->TaxID? 'selected' :'' }}>{{$data->Name}}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>

                                                    <div class="form-group mb-3" id="diskon">
                                                        <label for="title">Diskon (Rupiah)</label>
                                                        <input type="text" id="tanpa-rupiah-diskon" class="form-control" value="{{old('diskon','')}}">
                                                        <input type="hidden" id="diskonBarang" value=0>
                                                    </div>

                                                    <div class="form-group " id="ket">
                                                        <label for="Keterangan">Keterangan</label>
                                                        <textarea rows="3" id="keteranganBarang" class="form-control" value="{{old('keterangan','')}}"></textarea>
                                                    </div>



                                                    <input class="btn btn-danger btn-lg btn-block" type="button" id="tambahKeranjang" value="Tambah kedalam Keranjang">
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                            <!-- /.card -->

                                        </div>
                                        <!-- /.col (left) -->
                                        <div class="col-md-6">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Keranjang</h3>
                                                </div>
                                                <div class="card-body">
                                                    <!-- Date -->
                                                    <!--<input type="hidden" name="tanggalDibutuhkan" value="{{old('tanggalDibutuhkanVal')}}">
                                      <input type="hidden" name="gudang" value="{{old('tanggalDibutuhkanVal')}}">
                                      <input type="hidden" name="tanggalAkhir" value="{{old('tanggalAkhirVal')}}">-->
                                                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                                                        <span class="text-muted">Keranjang</span>
                                                        <span class="badge badge-secondary badge-pill" name="totalBarangnya" id="totalBarangnya" value={{count($dataDetail)}} totalKeranjang="{{count($dataDetail)}}">{{count($dataDetail)}}</span>
                                                    </h4>
                                                    <ul class="list-group mb-3 sticky-top" id="keranjang">
                                                        @foreach($dataDetail as $data)
                                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                            <div id="hiddenDiv">
                                                                <input type="hidden" class="cekId" name="itemId[]" value="{{$data->idItem}}">
                                                                <input type="hidden" id="cekJumlah" class="cekJumlah" name="itemTotal[]" value="{{(float)$data->jumlah}}">
                                                                <input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="{{$data->keterangan}}">
                                                                <input type="hidden" class="cekHarga" name="itemHarga[]" value="{{(float)$data->harga}}">
                                                                <input type="hidden" class="cekDiskon" name="itemDiskon[]" value="{{(float)$data->diskon}}">
                                                                <input type="hidden" class="cekTax" name="itemTax[]" value="{{$data->idTax}}">
                                                                <input type="hidden" class="cekTaxValue" name="itemTaxValue[]" value="{{$data->TaxPercent}}">
                                                                <input type="hidden" class="cekPrd" name="prdID[]" value="{{$data->idPurchaseRequestDetail}}">
                                                                @foreach($dataPurchaseRequestDetail as $dataPrd)
                                                                @if($dataPrd->id == $data->idPurchaseRequestDetail)
                                                                <input type="hidden" class="cekPr" name="prID[]" value="{{$dataPrd->idPurchaseRequest}}">
                                                                @endif
                                                                @endforeach
                                                                <h6 class="my-0">{{$data->itemName}}<small class="jumlahVal" value="{{(float)$data->jumlah}}"> ({{(float)$data->jumlah}})</small> </h6>
                                                                <small class="text-muted namaNppVal" value="{{$data->namaNPPCheck}}">NPP: {{$data->namaNPPCheck}}</small><br>
                                                                <small class="text-muted keteranganVal" value="{{$data->keterangan}}">{{$data->keterangan}}</small><br>
                                                                <small class="text-muted diskonVal" value="{{(float)$data->diskon}}">Diskon/Item: Rp. {{number_format((float)$data->diskon)}},-</small><br>
                                                                <small class="text-muted taxVal" value="{{$data->TaxPercent}}">Pajak: {{$data->TaxPercent}}%</small><br>
                                                                <small class="text-muted hargaSatuanVal" value="{{(float)$data->harga}}">Harga/Item: Rp. {{number_format((float)$data->harga)}},-</small><br>
                                                            </div>
                                                            <div>
                                                                <strong class="hargaVal" value="{{(((float)$data->harga- (float)$data->diskon) * $data->jumlah) * (100.0 + (float)$data->TaxPercent) / 100.0}}">Rp. {{number_format((((float)$data->harga- (float)$data->diskon) * $data->jumlah) * (100.0 + (float)$data->TaxPercent) / 100.0)}},-</strong>
                                                                <button class="btn btn-primary copyKe" type="button" id="copyKe">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                                    </svg>
                                                                </button>
                                                                <button class="btn btn-danger" type="button" id="hapusKeranjang">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </li>

                                                        @endforeach
                                                    </ul>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Total (Rupiah)</span>
                                                        <strong name="TotalHargaKeranjang" id="TotalHargaKeranjang" value=0 jumlahHarga={{$purchaseOrder->totalHarga}}>Rp. {{number_format($purchaseOrder->totalHarga)}},-</strong>
                                                    </li>
                                                    <!-- /.form group -->
                                                </div>
                                                <!---->
                                                <input class="btn btn-primary " type="submit" id="tambah" value="Kirim"><br>

                                                <!-- /.card-body -->
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                        <!-- /.col (right) -->
                                    </div>


</form>
</div>
</div>
<!-- /.col -->
</div>


<!-- this row will not appear when printing -->

<!-- /.invoice -->
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</form>

<script type="text/javascript">
    var tambahCombo = "";
    var totalTambah = parseInt($('#totalBarangnya').html());

    //$('#TotalHargaKeranjang').val(0);

    $(document).ready(function() {

        var id = $("#perusahaanID option:selected").val();
        var singkatan = $("#perusahaanID option:selected").attr('singkatan');
        var optionnya = '';

        var dataPurchaseRequest = <?php echo json_encode($dataPurchaseRequest); ?>;

        optionnya += '<option value="pilih" selected>--Permintaan Order--</option>\n';
        $.each(dataPurchaseRequest, function(key, value) {
            if (value.cidp.toString() == id.toString()) {
                optionnya += '<option id="preqID" idPr=' + value.id + ' value="' + value.id + '">' + value.name + '</option>\n';
            }
        });
        $("#pReq").empty();
        $("#pReq").append(optionnya);
        $('.selectpicker').selectpicker('refresh');

        $("#perusahaanID").on("change", function() {

            var id = this.value;
            //alert(id);
            var singkatan = $("#perusahaanID option:selected").attr('singkatan');
            //alert(singkatan);
            var optionnya = '';

            var dataPurchaseRequest = <?php echo json_encode($dataPurchaseRequest); ?>;

            //alert('masuk sini');
            optionnya += '<option value="pilih" selected>--Permintaan Order--</option>\n';
            $.each(dataPurchaseRequest, function(key, value) {

                if (value.cidp.toString() == id.toString()) {
                    //alert('masuk'); 
                    //alert("masuk cek");
                    optionnya += '<option id="preqID" idPr=' + value.id + ' value="' + value.id + '">' + value.name + '</option>\n';
                    //alert(optionnya);         
                }
            });



            $("#pReq").empty();
            $("#pReq").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
        });

        $("#pReq").change(function() {
            //alert(this.value);
            var id = this.value;
            var optionnya = '';
            //var dataBarangTag = <?php //echo json_encode($dataBarangTag); 
                                    ?>;
            var dataPurchaseRequestDetail = <?php echo json_encode($dataPurchaseRequestDetail); ?>;

            //alert('masuk sini');
            optionnya += '<option value="" selected>--Pilih barang--</option>\n';
            $.each(dataPurchaseRequestDetail, function(key, value) {
                //alert(value.ItemName);
                if (value.idPurchaseRequest.toString() == id.toString()) {
                    //alert('masuk');
                    optionnya += '<option id="namaBarang" idPr=' + value.ItemID + ' value="' + value.id + '" namaItem="'+value.ItemName+'">' + value.ItemName + '<nbsp>(' + value.UnitName + ')</option>\n';
                }
            });
            //alert(optionnya);


            $("#barang").empty();
            $("#barang").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
        });


        $("#barang").change(function() {
            //alert(this.value);
            var id = this.value;
            var dataPurchaseRequestDetail = <?php echo json_encode($dataPurchaseRequestDetail); ?>;
            var dataDetail = <?php echo json_encode($dataDetailBarang); ?>;

            $.each(dataPurchaseRequestDetail, function(key, value) {
                //alert(value.ItemName);
                if (value.id.toString() == id.toString()) {
                    /*if(parseFloat(value.podJumlah) !=  "null" || value.podJumlah !=  "" ||  parseFloat(value.podJumlah) !=  "NaN"|| parseFloat(value.podJumlah) <  0){
                        var maxAngka = parseFloat(value.jumlah);
                    }
                    else{
                        alert("masuk else barang ");
                        var maxAngka = parseFloat(value.jumlah) - parseFloat(value.jumlahProses);
                    }*/
                    var maxAngka = parseFloat(value.jumlah);
                    $.each(dataDetail, function(k, v) {
                        if (v.idPurchaseRequestDetail.toString() == value.id.toString() && value.ItemID.toString() == v.idItem.toString()) {
                            //alert("masuk minus");
                            maxAngka -= parseFloat(v.jumlah);
                        }
                    });
                    $.each($('.cekPrd'), function(idx, val) {
                        //alert("masuk sekarang")
                        if (val.value == value.id) {
                            var jumlahBarang = $('.cekJumlah:eq(' + idx + ')').val();
                            maxAngka = maxAngka - jumlahBarang;
                        }


                    });

                    //alert(maxAngka);
                    $("#jumlahBarang").attr({
                        "max": maxAngka,
                        "min": 1,
                        "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                        "value": "",
                    });
                    if (maxAngka <= 0) {
                        $('#jumlahBarang').prop('readonly', true);
                    } else {
                        $('#jumlahBarang').prop('readonly', false);
                    }
                }
            });

        });

    });

    $('body').on('click', '#copyKe', function() {
        //alert($(this).index('.copyKe'));
        var i = $(this).index('#copyKe');

        var idBarang = $('.cekId:eq(' + i + ')').val();
        //var namaBarang = $('.cekJumlah:eq('+i+')').val();
        var jumlahBarang = $('.cekJumlah:eq(' + i + ')').val();
        var pajak = $('.cekTax:eq(' + i + ')').val(); //cekTaxValue
        var prd = $('.cekPrd:eq(' + i + ')').val();
        var pr = $('.cekPr:eq(' + i + ')').val();

        var hargaBarang = $('.cekHarga:eq(' + i + ')').val();
        var keteranganBarang = $('.cekKeterangan:eq(' + i + ')').val();
        var diskonBarang = $('.cekDiskon:eq(' + i + ')').val();

        $("#pReq").val(pr).change();
        $("#barang").val(prd).change();
        $("#jumlahBarang").val(jumlahBarang);
        $("#hargaBarang").val(hargaBarang);
        $("#hargaBarang").html(hargaBarang);
        $("#tanpa-rupiah").val(formatRupiah(hargaBarang));
        $("#diskonBarang").val(diskonBarang);
        $("#tanpa-rupiah-diskon").val(formatRupiah(diskonBarang));
        $("#keteranganBarang").val(keteranganBarang);
        $("#keteranganBarang").html(keteranganBarang);
        $("#tax").val(pajak).change();
        //$("#barang").val(idBarang).change();

        $('.selectpicker').selectpicker('refresh');

    });



    $('body').on('click', '#hapusKeranjang', function() {
        //alert($('.cekId:eq(2)').val());
        //alert($('.cekId').length);cekJumlah
        var jumlah = $(this).parent().parent().children("#hiddenDiv").children(".cekJumlah").val();
        //alert(jumlah);
        $("#jumlahBarang").attr({
            "max": parseFloat($("#jumlahBarang").attr("max")) + parseFloat(jumlah),
            "min": 1,
            "placeholder": "Jumlah Barang (Maksimal: " + (parseFloat($("#jumlahBarang").attr("max")) + parseFloat(jumlah)) + ")",
            "value": "",
        });


        var i = $(this).index('#hapusKeranjang');
        var jumlahBarang = $('.cekJumlah:eq(' + i + ')').val();
        var hargaBarang = $('.cekHarga:eq(' + i + ')').val();
        var diskonBarang = $('.cekDiskon:eq(' + i + ')').val();
        var taxBarang = $('.cekTaxValue:eq(' + i + ')').val();

        $(this).parent().parent().remove();
        totalTambah -= 1;

        //alert(parseFloat(hargaBarang) - parseFloat(diskonBarang));
        var totalSekarang = parseFloat($("#TotalHargaKeranjang").attr('jumlahHarga')) - parseFloat((parseFloat(jumlahBarang) * (parseFloat(hargaBarang) - parseFloat(diskonBarang))) * (100.0 + parseFloat(taxBarang)) / 100.0);
        //alert(totalSekarang);
        $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalSekarang));
        $('#TotalHargaKeranjang').html("Rp. " + totalSekarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

        $('#totalBarangnya').val(totalTambah);
        $('#totalBarangnya').html(totalTambah);
    });

    $('body').on('click', '#tambahKeranjang', function() {
        var idPurchase = $("#pReq option:selected").val(); //tambahan NOTE
        var namaNPPCheck = $("#pReq option:selected").html(); //tambahan NOTE
        var idPurchaseDetail = $("#barang").val(); //
        var namaBarang = $("#barang option:selected").attr("namaItem").toString(); //
        var jumlahBarang = parseFloat($("#jumlahBarang").val()); //
        //alert(jumlahBarang);
        var hargaBarang = parseFloat($("#hargaBarang").val()); //
        //alert(hargaBarang);
        var diskonBarang = parseFloat($("#diskonBarang").val()); //
        //alert(diskonBarang);
        //alert(jumlahBarang);
        var keteranganBarang = $("#keteranganBarang").val(); //
        var taxPercent = parseFloat($("#tax option:selected").attr("taxPercent"));
        var taxId = $("#tax option:selected").val();
        var idBarang = $("#barang option:selected").attr("idPr");
        var totalHarga = 0;


        //alert(taxPercent);

        var jumlahMax = $("#jumlahBarang").prop("max");
        if(jumlahBarang > jumlahMax){
            alert("Jumlah barang melebihi batas maksimum"); 
            $('#jumlahBarang').val("");
        }
        else{
            var indexSama = null;
            for (let i = 0; i < $('.cekId').length; i++) {
                if ($('.cekId:eq(' + i + ')').val() == idBarang) {
                    if ($('.cekHarga:eq(' + i + ')').val() == hargaBarang) {
                        if ($('.cekTax:eq(' + i + ')').val() == taxId) {
                            if ($('.cekDiskon:eq(' + i + ')').val() == diskonBarang) {
                                if ($('.cekPrd:eq(' + i + ')').val() == idPurchaseDetail) {
                                    indexSama = i;
                                }
                            }
                        }
                    }
                }
            }
            if (idBarang == "" || namaBarang == "--Pilih barang--" || jumlahBarang <= 0 || jumlahBarang.toString() == "NaN" || jumlahBarang == null || hargaBarang == 0 || hargaBarang == "" || keteranganBarang == "" || parseFloat(jumlahBarang) > parseFloat($("#jumlahBarang").attr("max")) || taxId == "") {
                alert('Harap lengkapi atau isi data Barang dengan benar');
                die;
            }
            //alert(jumlahBarang + hargaBarang+ keteranganBarang);
            else if (indexSama != null) {
                var jumlah = $('.cekJumlah:eq(' + indexSama + ')').val();
                $('.cekJumlah:eq(' + indexSama + ')').val(parseFloat(jumlah) + parseFloat(jumlahBarang));
                var keterangan = $('.cekKeterangan:eq(' + indexSama + ')').val();
                //$('.cekKeterangan:eq('+indexSama+')').val(keterangan + ".\n" +keteranganBarang);
                $('.cekKeterangan:eq(' + indexSama + ')').val(keteranganBarang);

                $('.keteranganVal:eq(' + indexSama + ')').html($('.cekKeterangan:eq(' + indexSama + ')').val());
                $('.jumlahVal:eq(' + indexSama + ')').html(($('.cekJumlah:eq(' + indexSama + ')').val()));
                //$('.cekPrd:eq('+indexSama+')').attr("sekarang",1);

                $('.hargaVal:eq(' + indexSama + ')').html("Rp. " + ((parseFloat($('.cekJumlah:eq(' + indexSama + ')').val()) * (parseFloat(hargaBarang) - parseFloat(diskonBarang))) * (100.0 + taxPercent) / 100.0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',-');

                var maxAngka = parseFloat($("#jumlahBarang").attr("max")) - parseFloat(jumlahBarang);
                //alert(maxAngka);
                $("#jumlahBarang").attr({
                    "max": maxAngka,
                    "min": 0,
                    "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                    "value": "",
                });
                if (maxAngka <= 0) {
                    $('#jumlahBarang').prop('readonly', true);
                } else {
                    $('#jumlahBarang').prop('readonly', false);
                }

                var totalHargaKeranjang = $('#TotalHargaKeranjang').attr('jumlahHarga').replaceAll('.', '');

                totalHarga = ((hargaBarang - diskonBarang) * jumlahBarang) * ((100.0 + taxPercent) / 100.0);
                $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalHargaKeranjang) + parseFloat(totalHarga));
                $('#TotalHargaKeranjang').html("Rp." + $('#TotalHargaKeranjang').attr('jumlahHarga').toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

                $("#pReq").val("").change();
                $("#barang").val("").change();
                $("#jumlahBarang").val("");
                $("#hargaBarang").val("");
                $("#tanpa-rupiah").val(0);
                $("#diskonBarang").val(0);
                $("#tanpa-rupiah-diskon").val(0);
                $("#keteranganBarang").val("");
                $("#tax").val("").change();
                $('.selectpicker').selectpicker('refresh');
            } else {
                var htmlKeranjang = "";
                htmlKeranjang += '<li class="list-group-item d-flex justify-content-between lh-condensed">\n';
                htmlKeranjang += '<div id="hiddenDiv">\n';
                htmlKeranjang += '<input type="hidden" class="cekId" name="itemId[]" value="' + idBarang + '">\n';
                htmlKeranjang += '<input type="hidden" id="cekJumlah" class="cekJumlah" name="itemTotal[]" value="' + jumlahBarang + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="' + keteranganBarang + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekHarga" name="itemHarga[]" value="' + hargaBarang + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekDiskon" name="itemDiskon[]" value="' + diskonBarang + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekTax" name="itemTax[]" value="' + taxId + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekTaxValue" name="itemTaxValue[]" value="' + taxPercent + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekPrd" name="prdID[]" value="' + idPurchaseDetail + '">\n';
                htmlKeranjang += '<input type="hidden" class="cekPr" name="prID[]" value="' + idPurchase + '">\n';
                htmlKeranjang += '<h6 class="my-0">' + namaBarang + '<small class="jumlahVal" value="' + jumlahBarang + '">(' + jumlahBarang + ')</small> </h6>\n';
                htmlKeranjang += '<small class="text-muted namaNppVal" value="' + namaNPPCheck + '">NPP: ' + namaNPPCheck + '</small><br>\n';
                htmlKeranjang += '<small class="text-muted keteranganVal" value="' + keteranganBarang + '">' + keteranganBarang + '</small><br>\n';
                htmlKeranjang += '<small class="text-muted diskonVal" value="' + diskonBarang + '">Diskon/Item: Rp. ' + diskonBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</small><br>\n';
                htmlKeranjang += '<small class="text-muted taxVal" value="' + taxPercent + '">Pajak: ' + taxPercent + '%</small><br>\n';
                htmlKeranjang += '<small class="text-muted hargaSatuanVal" value="' + hargaBarang + '">Harga/Item : Rp. ' + hargaBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</small><br>\n';
                htmlKeranjang += '</div>\n';
                htmlKeranjang += '<div>\n';
                htmlKeranjang += '<strong class="hargaVal" value="' + ((hargaBarang - diskonBarang) * jumlahBarang) * (100.0 + taxPercent) / 100.0 + '">Rp. ' + (((hargaBarang - diskonBarang) * jumlahBarang) * (100.0 + taxPercent) / 100.0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ',-</strong>\n';
                htmlKeranjang += '<button class="btn btn-primary copyKe" type="button" id="copyKe">\n';
                htmlKeranjang += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">\n';
                htmlKeranjang += '<path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>\n';
                htmlKeranjang += '</svg>\n';
                htmlKeranjang += '</button>\n';
                htmlKeranjang += '<button class="btn btn-danger" type="button" id="hapusKeranjang">\n';
                htmlKeranjang += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">\n';
                htmlKeranjang += '<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>\n';
                htmlKeranjang += '</svg>\n';
                htmlKeranjang += '</button>\n';
                htmlKeranjang += '</div>\n';
                htmlKeranjang += '</li>\n';

                $('#keranjang').append(htmlKeranjang);
                totalTambah += 1
                $('#totalBarangnya').val(totalTambah);
                $('#totalBarangnya').html(totalTambah);

                var maxAngka = parseFloat($("#jumlahBarang").attr("max")) - parseFloat(jumlahBarang);
                //alert(maxAngka);
                $("#jumlahBarang").attr({
                    "max": maxAngka,
                    "min": 0,
                    "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                    "value": "",
                });
                if (maxAngka <= 0) {
                    $('#jumlahBarang').prop('readonly', true);
                } else {
                    $('#jumlahBarang').prop('readonly', false);
                }

                /*var totalHargaKeranjang = parseFloat($('#TotalHargaKeranjang').val());
                alert(totalHargaKeranjang);
                totalHargaKeranjang += ((hargaBarang-diskonBarang) * jumlahBarang) * (100.0+taxPercent) / 100.0;
                alert(totalHargaKeranjang);
                $('#TotalHargaKeranjang').html(totalHargaKeranjang);
                $('#TotalHargaKeranjang').val(totalHargaKeranjang);*/

                var totalHargaKeranjang = $('#TotalHargaKeranjang').attr('jumlahHarga').replaceAll('.', '');

                totalHarga = ((hargaBarang - diskonBarang) * jumlahBarang) * (100.0 + taxPercent) / 100.0;
                $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalHargaKeranjang) + parseFloat(totalHarga));
                $('#TotalHargaKeranjang').html("Rp." + $('#TotalHargaKeranjang').attr('jumlahHarga').toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));


                $("#pReq").val("").change();
                $("#barang").val("").change();
                $("#jumlahBarang").val("");
                $("#hargaBarang").val("");
                $("#tanpa-rupiah").val(0);
                $("#diskonBarang").val(0);
                $("#tanpa-rupiah-diskon").val(0);
                $("#keteranganBarang").val("");
                $("#tax").val("").change();
                $('.selectpicker').selectpicker('refresh');
            }
        }

        


    });


    $("body").on("click", "#tambah", function() {
        totalTambah++;
        tambahCombo += '<div class="form-group p-3 mb-2 bg-light text-dark border" id="tmbhBarangJasa' + totalTambah + '">\n';
        tambahCombo += '<input type="hidden" name="totalRequest[]">\n';
        tambahCombo += '<div class="form-group" id="tmbhBarang">\n';
        tambahCombo += '<label for="title">Barang</label>\n';
        tambahCombo += '<select require name="barang[]" class="form-control" id="barang' + totalTambah + '">\n';
        tambahCombo += '<option value="">--Pilih barang--</option>\n';
        tambahCombo += '@foreach($dataBarang as $key => $data)\n';
        tambahCombo += '<option name="idBarang" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? "selected" :"" }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})  </option>\n';
        tambahCombo += '@endforeach\n';
        tambahCombo += '</select>\n';
        tambahCombo += '<input min=1 require name="jumlah[]" id="jml" type="number" class="form-control" placeholder="Jumlah barang" aria-label="Recipient' + "'" + 's username" aria-describedby="basic-addon2"id="angka" />\n';
        tambahCombo += '<br id="br">\n';
        tambahCombo += '</div>\n';
        tambahCombo += '<div class="form-group" id="harga' + totalTambah + '">\n';
        tambahCombo += '<label for="title">Harga</label>\n';
        tambahCombo += '<input require type="number" name="harga[]" class="form-control">\n';
        tambahCombo += '</div>\n';
        tambahCombo += '<div class="form-group" id="ket' + totalTambah + '">\n';
        tambahCombo += '<label for="title">Keterangan</label>\n';
        tambahCombo += '<input require type="text" name="Keterangan[]" class="form-control" >\n';
        tambahCombo += '</div>\n';
        tambahCombo += '</div>';

        $('#totalRequest').append(tambahCombo);
        tambahCombo = "";
    });
    $("body").on("click", "#kurang", function() {
        //$('#barang'+ totalTambah).remove();//i
        //$('#jml'+ totalTambah).remove();//i
        //$('#br'+ totalTambah).remove();//i
        $('#tmbhBarangJasa' + totalTambah).remove(); //i
        if (totalTambah > 0) {
            totalTambah--;
        }
    });

    /* Tanpa Rupiah */
    var tanpa_rupiah = document.getElementById('tanpa-rupiah');
    tanpa_rupiah.addEventListener('keyup', function(e) {
        $('#hargaBarang').val(this.value.toString().replaceAll(/\./g, ''));
        //alert(this.value.toString().replace(/\./g, ''));
        tanpa_rupiah.value = formatRupiah(this.value);
    });

    var tanpa_rupiah_diskon = document.getElementById('tanpa-rupiah-diskon');
    tanpa_rupiah_diskon.addEventListener('keyup', function(e) {
        $('#diskonBarang').val(this.value.toString().replaceAll(/\./g, ''));
        tanpa_rupiah_diskon.value = formatRupiah(this.value);
    });

    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('dengan-rupiah');
    dengan_rupiah.addEventListener('keyup', function(e) {
        $('#hargaBarang').val(this.value.toString().replaceAll(/\./g, ''));
        dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $(document).ready(function() {
        $('input[name=jenis0]').click(function() {

            if ($("#barang").is(':checked')) {
                $("#tmbhBarang").show();
                $("#total").show();
                $("#ket").hide();
                //$("#buttonBarang").show();
            } else {
                $("#ket").show();
                $("#tmbhBarang").hide();
                //$("#buttonBarang").hide();
            }
        });
    });

    /*$('#myForm input').on('change', function() {
           $("input[name=jenis]").change(function(){

            if($("#barang").is(':checked'))
            {
              $("#tmbhBarang").show();
              $("#total").show();
               $("#ket").hide();
               //$("#buttonBarang").show();
            }
            else
            {
              $("#ket").show();
              $("#tmbhBarang").hide();
              //$("#buttonBarang").hide();
            }
        });
      //alert($('input[name=jenis]:checked', '#myForm').val()); 
    });*/
</script>
@endsection