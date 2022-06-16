@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Edit Surat Jalan
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('suratJalan.index')}}">Surat Jalan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{route('suratJalan.update', [$suratJalan->id])}}" method="POST">
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
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Tanggal Pembuatan</label>
                                            <input readonly name="tanggalDibuat" type="date" class="form-control" id="datePembuatan" placeholder="" value="{{$suratJalan->tanggalDibuat}}" required="">
                                            <div class="invalid-feedback"> Valid last name is required. </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Pilih Gudang Awal</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangIDAwal">
                                                    <option value="">
                                                        --Pilih Gudang Awal--
                                                    </option>
                                                    @foreach($dataGudang as $key => $data)
                                                    @if($data->MGudangID == $suratJalan->MGudangIDAwal)
                                                    <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                                    @else
                                                    <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                                    @endif
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Pilih Gudang Tujuan</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudangTujuan" name="MGudangIDTujuan">
                                                    <option value="">
                                                        --Pilih Gudang Tujuan--
                                                    </option>
                                                    @foreach($dataGudang as $key => $data)
                                                    @if($data->MGudangID == $suratJalan->MGudangIDTujuan)
                                                    <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                                    @else
                                                    <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                                    @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastName">Data Purchase Request</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="PurchaseRequestID" id="PurchaseRequestID">
                                                    @foreach($dataPurchaseRequest as $key => $data)
                                                    @if($data->MGudangID == $suratJalan->MGudangIDTujuan)
                                                    @if($data->id == $suratJalan->PurchaseRequestID)
                                                    <option selected value="{{$data->id}}" {{$data->name == $data->id? 'selected' :'' }}>{{$data->name}} -{{date("d-m-Y", strtotime($data->tanggalDibuat))}}</option>

                                                    @else
                                                    <option value="{{$data->id}}" {{$data->name == $data->id? 'selected' :'' }}>{{$data->name}} -{{date("d-m-Y", strtotime($data->tanggalDibuat))}}</option>

                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Kendaraan</label>
                                            <textarea rows="3" type="text" name="keteranganKendaraan" class="form-control" value="{{old('keteranganKendaraan',$suratJalan->keteranganKendaraan)}}">{{$suratJalan->keteranganKendaraan}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Nomor Polisi</label>
                                            <textarea rows="3" type="text" name="keteranganNomorPolisi" class="form-control" value="{{old('keteranganNomorPolisi',$suratJalan->keteranganNomorPolisi)}}">{{$suratJalan->keteranganNomorPolisi}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Pemudi</label>
                                            <textarea rows="3" type="text" name="keteranganPemudi" class="form-control" value="{{old('keteranganPemudi',$suratJalan->keteranganPemudi)}}">{{$suratJalan->keteranganPemudi}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Transaksi</label>
                                            <textarea rows="3" type="text" name="keteranganTransaksi" class="form-control" value="{{old('keteranganTransaksi',$suratJalan->keteranganTransaksi)}}">{{$suratJalan->keteranganTransaksi}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Gudang Tujuan</label>
                                            <textarea rows="3" type="text" name="keteranganGudangTujuan" class="form-control" value="{{old('keteranganGudangTujuan',$suratJalan->keteranganGudangTujuan)}}">{{$suratJalan->keteranganGudangTujuan}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Keterangan Penerima</label>
                                            <textarea rows="3" type="text" name="keteranganPenerima" class="form-control" value="{{old('keteranganPenerima',$suratJalan->keteranganPenerima)}}">{{$suratJalan->keteranganPenerima}}</textarea>
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

                                                    <div class="form-group" id='tmbhBarang'>
                                                        <label for="title">Barang</label>
                                                        <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="barang" id="barang">

                                                            <option value="pilih">--Pilih barang--</option>
                                                            <!--@foreach($dataBarang as $key => $data)
                                        <option id="namaBarang" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})</option>
                                        @endforeach-->
                                                        </select>
                                                        <input id="jumlahBarang" value="1" min="1" type="number" step=".01" class="form-control" placeholder="Jumlah barang" aria-label="Recipient's username" aria-describedby="basic-addon2" />
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
                                                        <span class="badge badge-secondary badge-pill" name="totalBarangnya" id="totalBarangnya" value="{{count($dataTotalDetail)}}">{{count($dataTotalDetail)}}</span>
                                                    </h4>
                                                    <ul class="list-group mb-3 sticky-top" id="keranjang">
                                                        @foreach($dataTotalDetail as $data)
                                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                                            <div id="hiddenDiv">
                                                                <input type="hidden" class="cekId" name="itemId[]" value="{{$data->ItemID}}">
                                                                <input type="hidden" id="cekJumlah" class="cekJumlah" name="itemJumlah[]" value="{{$data->jumlah}}">
                                                                <input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="{{$data->keterangan}}">
                                                                <input type="hidden" class="cekPrd" name="itemPRDID[]" value="{{$data->idPRD}}">
                                                                <h6 class="my-0">{{$data->itemName}}<small class="jumlahVal" value="{{$data->jumlah}}">({{$data->jumlah}})</small> </h6>
                                                                <small class="text-muted keteranganVal" value="{{$data->keterangan}}">{{$data->keterangan}}</small><br>
                                                            </div>
                                                            <div>
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
                                                    <!--<li class="list-group-item d-flex justify-content-between">
                                              <span>Total (Rupiah)</span>
                                              <strong name="TotalHargaKeranjang" id="TotalHargaKeranjang" value=0 jumlahHarga=0>0</strong>
                                      </li>-->
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
    var totalTambah = 0;
    $('#TotalHargaKeranjang').val(0);




    $(document).ready(function() {
        /*var id = $("#idGudangTujuan option:selected").val();
        var optionnya = '';
        var dataPurchaseRequest = <?php echo json_encode($dataPurchaseRequest); ?>;
        var suratJalan = <?php echo json_encode($suratJalan); ?>;
        //alert('masuk sini');
        optionnya += '<option value="pilih" selected>--Pilih Purchase Request--</option>\n';
        $.each(dataPurchaseRequest, function(key, value) {

            if (value.MGudangID.toString() == id.toString()) {
                //alert('masuk'); 
                if (suratJalan.PurchaseRequestID == value.id) {
                    optionnya += '<option selected id="idPr" value="' + value.id + '">' + value.name + '-(' + value.tanggalDibuat + ')</option>\n';
                } else {
                    optionnya += '<option id="idPr" value="' + value.id + '">' + value.name + '-(' + value.tanggalDibuat + ')</option>\n';
                }
                //alert(optionnya);         
            }
        });


        $("#PurchaseRequestID").empty();
        $("#PurchaseRequestID").append(optionnya);*/
        var id = $("#PurchaseRequestID option:selected").val();
        var optionnya = '';
        var dataPurchaseRequestDetail = <?php echo json_encode($dataPurchaseRequestDetail); ?>;

        //alert('masuk sini');
        optionnya += '<option value="pilih" selected>--Pilih Barang--</option>\n';
        $.each(dataPurchaseRequestDetail, function(key, value) {
            if (value.idPurchaseRequest.toString() == id.toString()) {
                optionnya += '<option id="namaBarang" namaBarang=' + value.ItemName + ' idPrdId=' + value.id + ' value="' + value.ItemID + '">' + value.ItemName + '<nbsp>(' + value.UnitName + ')</option>\n';
            }
        });
        $("#barang").empty();
        $("#barang").append(optionnya);
        $('.selectpicker').selectpicker('refresh');

        $("#idGudangTujuan").on("change", function() { //sudah

            var id = this.value;
            //alert(id);
            //var singkatan = $("#perusahaanID option:selected").attr('singkatan');
            //alert(singkatan);
            var optionnya = '';

            var dataPurchaseRequest = <?php echo json_encode($dataPurchaseRequest); ?>;

            //alert('masuk sini');
            optionnya += '<option value="pilih" selected>--Pilih Purchase Request--</option>\n';
            $.each(dataPurchaseRequest, function(key, value) {

                if (value.MGudangID.toString() == id.toString()) {
                    //alert('masuk'); 
                    optionnya += '<option id="idPr" value="' + value.id + '">' + value.name + '-(' + value.tanggalDibuat + ')</option>\n';
                    //alert(optionnya);         
                }
            });


            $("#PurchaseRequestID").empty();
            $("#PurchaseRequestID").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
            $('#keranjang').empty();


        });

        $("#PurchaseRequestID").on("change", function() { //sudah

            var id = this.value;
            //alert(id);
            //var singkatan = $("#perusahaanID option:selected").attr('singkatan');
            //alert(singkatan);
            var optionnya = '';

            var dataPurchaseRequestDetail = <?php echo json_encode($dataPurchaseRequestDetail); ?>;

            //alert('masuk sini');
            optionnya += '<option value="pilih" selected>--Pilih Barang--</option>\n';
            $.each(dataPurchaseRequestDetail, function(key, value) {

                if (value.idPurchaseRequest.toString() == id.toString()) {
                    //alert('masuk'); 
                    //alert("masuk cek");
                    optionnya += '<option id="namaBarang" namaBarang=' + value.ItemName + ' idPrdId=' + value.id + ' value="' + value.ItemID + '">' + value.ItemName + '<nbsp>(' + value.UnitName + ')</option>\n';
                    //alert(optionnya);         
                }
            });


            $("#barang").empty();
            $("#barang").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
            $('#keranjang').empty();
        });

        $("#barang").on("change", function() { //sudah

            var id = this.value;
            var idPrdID = $("#barang option:selected").attr('idPrdId');
            var idGudang = $("#idGudang option:selected").val();
            var datePembuatan = $("#datePembuatan").val();
            var optionnya = '';
            var maxAngka = 0;

            var dataReportUntukStok = <?php echo json_encode($dataReportUntukStok); ?>;
            $.each(dataReportUntukStok, function(key, value) {
                if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString() && value.Date <= datePembuatan) {  
                    maxAngka = maxAngka + parseFloat(value.Quantity);
                }

            });

            $.each($('.cekId'), function(idx, val) {
                if (val.value == id) {
                    var jumlahBarang = $('.cekJumlah:eq(' + idx + ')').val();
                    maxAngka = maxAngka - jumlahBarang;
                }
            });

            /*var dataTotalDetail = <?php echo json_encode($dataTotalDetail); ?>;
            $.each(dataTotalDetail, function(k, v) {
                if (v.idPRD.toString() == idPrdID.toString() && v.ItemID.toString() == id.toString()) {
                    //alert("masuk minus");
                    maxAngka -= parseFloat(v.jumlah);
                }
            });*/
            if (maxAngka > 0) {
                $("#jumlahBarang").attr({
                    "max": maxAngka,
                    "min": 1,
                    "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                    "value": "",
                });
            } else {
                $("#jumlahBarang").attr({
                    "max": 0,
                    "min": 0,
                    "placeholder": "Tidak ada barang di gudang",
                    "value": "",
                });
            }
            if (maxAngka <= 0) {
                $('#jumlahBarang').prop('readonly', true);
            } else {
                $('#jumlahBarang').prop('readonly', false);
            }
            //$('#keranjang').empty();
        });


        $("#idGudang").on("change", function() { //sudah

            var id = $("#barang option:selected").attr();
            var idPrdID = $("#barang option:selected").attr('idPrdId');
            var idGudang = $("#idGudang option:selected").val();
            var datePembuatan = $("#datePembuatan").val();
            var optionnya = '';
            var maxAngka = 0;
            if (id == "pilih" || id == "") {
                $("#jumlahBarang").attr({
                    "max": 0,
                    "min": 0,
                    "placeholder": "Belum memilih barang",
                    "value": "",
                });

            } else {
                var dataReportUntukStok = <?php echo json_encode($dataReportUntukStok); ?>;
                $.each(dataReportUntukStok, function(key, value) {
                    if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString() && value.Date <= datePembuatan) {
                        //$("#stokAwalBarang").val(value.totalQuantity);     
                        maxAngka = maxAngka + parseFloat(value.Quantity);
                    }
                });
                $.each($('.cekId'), function(idx, val) {
                if (val.value == id) {
                    var jumlahBarang = $('.cekJumlah:eq(' + idx + ')').val();
                    maxAngka = maxAngka - jumlahBarang;
                    }
                });
                /*var dataTotalDetail = <?php echo json_encode($dataTotalDetail); ?>;
                $.each(dataTotalDetail, function(k, v) {
                    if (v.idPRD.toString() == idPrdID.toString() && v.ItemID.toString() == id.toString()) {
                        //alert("masuk minus");
                        maxAngka -= parseFloat(v.jumlah);
                    }
                });*/
                if (maxAngka > 0) {
                    $("#jumlahBarang").attr({
                        "max": maxAngka,
                        "min": 1,
                        "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                        "value": "",
                    });
                } else {
                    $("#jumlahBarang").attr({
                        "max": 0,
                        "min": 0,
                        "placeholder": "Tidak ada barang di gudang",
                        "value": "",
                    });
                }
                if (maxAngka <= 0) {
                    $('#jumlahBarang').prop('readonly', true);
                } else {
                    $('#jumlahBarang').prop('readonly', false);
                }
            }
            $('#keranjang').empty();


        });


        $("#datePembuatan").on("change", function() {
            var id = $("#ItemID option:selected").val();
            if (id == "pilih" || id == "") {
                $("#jumlahBarang").attr({
                    "max": 0,
                    "min": 0,
                    "placeholder": "Belum memilih barang",
                    "value": "",
                });

            } else {
                var idGudang = $("#idGudang option:selected").val();
                var datePembuatan = $("#datePembuatan").val();
                var optionnya = '';
                var maxAngka = 0;

                var dataReportUntukStok = <?php echo json_encode($dataReportUntukStok); ?>;
                $.each(dataReportUntukStok, function(key, value) {
                    if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString() && value.Date <= datePembuatan) {
                        //$("#stokAwalBarang").val(value.totalQuantity);     
                        maxAngka = maxAngka + parseFloat(value.Quantity);
                    }

                });
                $.each($('.cekId'), function(idx, val) {
                if (val.value == id) {
                    var jumlahBarang = $('.cekJumlah:eq(' + idx + ')').val();
                    maxAngka = maxAngka - jumlahBarang;
                    }
                });

                /*var dataTotalDetail = <?php echo json_encode($dataTotalDetail); ?>;
                $.each(dataTotalDetail, function(k, v) {
                    if (v.idPRD.toString() == idPrdID.toString() && v.ItemID.toString() == id.toString()) {
                        //alert("masuk minus");
                        maxAngka -= parseFloat(v.jumlah);
                    }
                });*/
                if (maxAngka > 0) {
                    $("#jumlahBarang").attr({
                        "max": maxAngka,
                        "min": 1,
                        "placeholder": "Jumlah Barang (Maksimal: " + maxAngka + ")",
                        "value": "",
                    });
                } else {
                    $("#jumlahBarang").attr({
                        "max": 0,
                        "min": 0,
                        "placeholder": "Tidak ada barang di gudang",
                        "value": "",
                    });
                }

                if (maxAngka <= 0) {
                    $('#jumlahBarang').prop('readonly', true);
                } else {
                    $('#jumlahBarang').prop('readonly', false);
                }

            }

            $('#keranjang').empty();

        });
    });

    $('body').on('click', '#copyKe', function() { //belum
        //alert($(this).index('.copyKe'));
        var i = $(this).index('#copyKe');

        var idBarang = $('.cekId:eq(' + i + ')').val();
        var jumlahBarang = $('.cekJumlah:eq(' + i + ')').val();

        var hargaBarang = $('.cekHarga:eq(' + i + ')').val();
        var keteranganBarang = $('.cekKeterangan:eq(' + i + ')').val();
        var diskonBarang = $('.cekDiskon:eq(' + i + ')').val();

        $("#barang").val(idBarang);
        $("#jumlahBarang").val(jumlahBarang);
        $("#keteranganBarang").val(keteranganBarang);

        //$('.selectpicker').selectpicker('refresh');

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
        $(this).parent().parent().remove();
        var totalSekarang = $('#totalBarangnya').attr("value");
        totalSekarang -= 1
        $('#totalBarangnya').val(totalSekarang);
        $('#totalBarangnya').html(totalSekarang);
    });

    $('body').on('click', '#tambahKeranjang', function() {

        var idBarang = $("#barang").val(); //
        var namaBarang = $("#barang option:selected").html(); //
        var idprdID = $("#barang option:selected").attr("idPrdId");
        //alert(idprdID);
        //var hargaBarang = $("#barang option:selected").attr("harga");
        var jumlahBarang = parseFloat($("#jumlahBarang").val()); //
        var keteranganBarang = $("#keteranganBarang").val(); //

        var indexSama = null;
        for (let i = 0; i < $('.cekId').length; i++) {
            if ($('.cekId:eq(' + i + ')').val() == idBarang) {
                if ($('.cekPrd:eq(' + i + ')').val() == idprdID) {
                    indexSama = i;
                }
            }
        }

        if (idBarang == "" || namaBarang == "--Pilih Barang--" || jumlahBarang <= 0 || jumlahBarang.toString() == "NaN" || jumlahBarang == null || keteranganBarang == "") {
            alert('Harap lengkapi atau isi data Barang dengan benar');
            die;
        }
          else if (jumlahBarang > $("#jumlahBarang").attr("max"))
        {
                $('#jumlahBarang').val("");
                alert("harap masukkan jumlah barang yang sesuai");
        }
         else if (indexSama != null) {
            //alert("masuk indexSama");
            var jumlah = $('.cekJumlah:eq(' + indexSama + ')').val();
            $('.cekJumlah:eq(' + indexSama + ')').val(parseFloat(jumlah) + parseFloat(jumlahBarang));
            var keterangan = $('.cekKeterangan:eq(' + indexSama + ')').val();
            $('.cekKeterangan:eq(' + indexSama + ')').val(keterangan + ".\n" + keteranganBarang);

            $('.keteranganVal:eq(' + indexSama + ')').html($('.cekKeterangan:eq(' + indexSama + ')').val());
            $('.jumlahVal:eq(' + indexSama + ')').html(($('.cekJumlah:eq(' + indexSama + ')').val()));

            var maxAngka = parseFloat($("#jumlahBarang").attr("max")) - parseFloat(jumlahBarang);
        } else {
            //alert("masuk");
            var htmlKeranjang = "";
            htmlKeranjang += '<li class="list-group-item d-flex justify-content-between lh-condensed">\n';
            htmlKeranjang += '<div id="hiddenDiv">\n';
            htmlKeranjang += '<input type="hidden" class="cekId" name="itemId[]" value="' + idBarang + '">\n';
            htmlKeranjang += '<input type="hidden" id="cekJumlah" class="cekJumlah" name="itemJumlah[]" value="' + jumlahBarang + '">\n';
            htmlKeranjang += '<input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="' + keteranganBarang + '">\n';
            //htmlKeranjang += '<input type="hidden" class="cekHarga" name="itemHarga[]" value="'+hargaBarang+'">\n';
            htmlKeranjang += '<input type="hidden" class="cekPrd" name="itemPRDID[]" value="' + idprdID + '">\n';
            htmlKeranjang += '<h6 class="my-0">' + namaBarang + '<small class="jumlahVal" value="' + jumlahBarang + '">(' + jumlahBarang + ')</small> </h6>\n';
            htmlKeranjang += '<small class="text-muted keteranganVal" value="' + keteranganBarang + '">' + keteranganBarang + '</small><br>\n';
            htmlKeranjang += '</div>\n';
            htmlKeranjang += '<div>\n';
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

        }
        $("#barang").val("").change(); //
        $("#jumlahBarang").val(""); //
        $("#keteranganBarang").val(""); //

    });

    /* Tanpa Rupiah */
    var tanpa_rupiah = document.getElementById('tanpa-rupiah');
    tanpa_rupiah.addEventListener('keyup', function(e) {
        $('#hargaBarang').val(this.value.toString().replace(/\./g, ''));
        //alert(this.value.toString().replace(/\./g, ''));
        tanpa_rupiah.value = formatRupiah(this.value);
    });

    var tanpa_rupiah_diskon = document.getElementById('tanpa-rupiah-diskon');
    tanpa_rupiah_diskon.addEventListener('keyup', function(e) {
        $('#diskonBarang').val(this.value.toString().replace(/\./g, ''));
        tanpa_rupiah_diskon.value = formatRupiah(this.value);
    });

    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('dengan-rupiah');
    dengan_rupiah.addEventListener('keyup', function(e) {
        $('#hargaBarang').val(this.value.toString().replace(/\./g, ''));
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