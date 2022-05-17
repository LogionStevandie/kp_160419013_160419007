@extends('layouts.home_master')
<style>
  p {
    font-family: 'Nunito', sans-serif;
  }
</style>

@section('judul')
Pembuatan Nota Permintaan Pembelian
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('purchaseRequest.index')}}">Nota-Permintaan-Pembelian</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="card card-primary">
    <!-- form start -->


    <form action="{{route('purchaseRequest.store')}}" method="POST">
      @csrf
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
                        <div class="col-md-6 mb-4">
                          <label for="firstName">Nama NPP</label>
                          <input type="text" class="form-control" id="firstName" placeholder="" value="{{$namaNpp}}" readonly required="" name="name">
                          <div class="invalid-feedback"> Valid first name is required. </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="lastName">Tanggal Pembuatan</label>
                          <input type="date" class="form-control" id="tanggalDibuat" placeholder="" value="{{$date}}" required="" name="tanggalDibuat">
                          <div class="invalid-feedback"> Valid last name is required. </div>
                        </div>




                        <div class="form-group col-md-6">
                          <label>Tanggal Awal - Akhir:</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            <input type="text" name="tanggalDibutuhkan" class="form-control float-right" id="reservation" value="{{old('tanggalDibutuhkan','')}}">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Pilih Gudang</label>
                            <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="gudang" id="gudang">
                              @foreach($dataGudang as $key => $data)
                              <option name="idGudang" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                              @endforeach
                            </select>
                          </div>

                        </div>

                        <div class="col-md-6 mb-3">
                          <label for="lastName">Jenis Permintaan</label>
                          <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" name="jenisProses">
                            <option value="1" selected>Pembelian Melalui Pusat</option>
                            <option value="0">Pembelian Melalui Lokal</option>
                          </select>
                        </div>

                        <!-- <div class="col-md-6">
                                    <div class="form-group" for="lastName">
                                    <label>Minimal</label>
                                    <select class="form-control select2" style="width: 100%;" name="jenisProses">
                                        <option value="1" selected>Pembelian Melalui Pusat</option>
                                        <option value="0">Pembelian Melalui Lokal</option>
                                    </select>
                                    </div>
                                 
                              </div>-->




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

                              <div class="form-group">
                                <label>Tag Barang</label>
                                <select class="form-control selectpicker" id="tagBarang" data-live-search="true" data-show-subtext="true">
                                  <option value="pilih">--Pilih Tag--</option>
                                  @foreach($dataTag as $key => $data)
                                  <option id="namaTag" value="{{$data->ItemTagID}}" {{$data->Name == $data->ItemTagID? 'selected' :'' }}>{{$data->Name}}</option>
                                  @endforeach
                                </select>
                              </div>

                              <div class="form-group">
                                <label>Barang</label>
                                <select class="form-control selectpicker" id="barang" data-live-search="true" data-show-subtext="true">
                                  <option value="pilih">--Pilih barang--</option>
                                  @foreach($dataBarang as $key => $data)
                                  <option id="namaBarang" value="{{$data->ItemID}}" {{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}
                                    <nbsp>({{$data->unitName}})
                                  </option>
                                  @endforeach
                                </select>
                                <br>
                                <input min=1 type="number" step=".01" class="form-control" placeholder="Jumlah barang" aria-label="Recipient's username" aria-describedby="basic-addon2" id="jumlahBarang" />
                              </div>


                              <div class="form-group" id="harga">
                                <label for="Harga">Harga</label>
                                <input type="text" step=".01" id="tanpa-rupiah" class="form-control" value="{{old('harga','')}}">
                                <input type="hidden" id="hargaBarang" value="">
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
                                <span class="badge badge-secondary badge-pill" name="totalBarangnya" id="totalBarangnya" value="0">0</span>
                              </h4>
                              <ul class="list-group mb-3 sticky-top" id="keranjang">
                                <!--<li class="list-group-item d-flex justify-content-between lh-condensed">
                                              <div>
                                                  <input type="hidden" name="itemId[]" value="">
                                                  <input type="hidden" name="itemTotal[]" value="">
                                                  <input type="hidden" name="itemKeterangan[]" value="">
                                                  <input type="hidden" name="itemHarga[]" value="">
                                                  <h6 class="my-0">Product name <small>(6)</small> </h6> 
                                                  <small class="text-muted">Keterangan</small><br>                      
                                              </div>
                                              <div>
                                                  <strong>$20</strong>
                                                  <button class="btn btn-danger" type="button" id="hapusKeranjang">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                          <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                                      </svg>
                                                  </button>
                                              </div>
                                          </li>     -->
                              </ul>
                              <li class="list-group-item d-flex justify-content-between">
                                <span>Total (Rupiah)</span>
                                <strong name="TotalHargaKeranjang" id="TotalHargaKeranjang" value=0 jumlahHarga=0>0</strong>
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
</div>

</div>

<script type="text/javascript">
  var tambahCombo = "";
  var totalTambah = 0;

  $(document).ready(function() {

    $("#tagBarang").on("change", function() {

      var id = this.value;
      var optionnya = '';

      var dataBarangTag = <?php echo json_encode($dataBarangTag); ?>;

      //<option id="namaBarang" value="{{$data->ItemID}}" {{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})</option>

      optionnya += '<option value="pilih" selected>--Pilih barang--</option>\n';
      $.each(dataBarangTag, function(key, value) {
        if (value.ItemTagID.toString() == id.toString()) {
          optionnya += '<option id="namaBarang" value="' + value.ItemID + '" {{' + value.ItemName + ' == '+value.ItemID+'? "selected" :"" }}>' + value.ItemName + '('+value.unitName+')' + '</option>\n';       
        }
      });
      $("#barang").empty();
      $("#barang").append(optionnya);
      $('.selectpicker').selectpicker('refresh');
    });

  });


  $('body').on('click', '#hapusKeranjang', function() {
    //alert($('.cekId:eq(2)').val());
    //alert($('.cekId').length);

    //var harga = $(this).parent();
    var i = $(this).index('#hapusKeranjang');
    var jumlahBarang = $('.cekJumlah:eq(' + i + ')').val();
    var hargaBarang = $('.cekHarga:eq(' + i + ')').val();
    //alert(jumlahBarang * hargaBarang);
    $(this).parent().parent().remove();

    totalTambah -= 1;
    //alert(parseFloat($("#TotalHargaKeranjang").attr('jumlahHarga')) - parseFloat(jumlahBarang * hargaBarang));
    var totalSekarang = parseFloat($("#TotalHargaKeranjang").attr('jumlahHarga')) - parseFloat(jumlahBarang * hargaBarang);
    $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalSekarang));
    $('#TotalHargaKeranjang').html("Rp. " + totalSekarang);


    $('#totalBarangnya').val(totalTambah);
    $('#totalBarangnya').html(totalTambah);
  });

  $('body').on('click', '#tambahKeranjang', function() {
    var idBarang = $("#barang").val();
    var namaBarang = $("#barang option:selected").html();
    var jumlahBarang = $("#jumlahBarang").val();
    var hargaBarang = $("#hargaBarang").val();
    var keteranganBarang = $("#keteranganBarang").val();
    var totalHarga = 0;
    //var totalHarga = jumlahBarang * hargaBarang;
    /*alert(totalHarga);
    alert(jumlahBarang);
    alert(hargaBarang);*/
    //die;

    var indexSama = null;
    for (let i = 0; i < $('.cekId').length; i++) {
      if ($('.cekId:eq(' + i + ')').val() == idBarang) {
        if ($('.cekHarga:eq(' + i + ')').val() == hargaBarang) {
          indexSama = i;
        }
      }
    }

    if (idBarang == "" || namaBarang == "--Pilih barang--" || jumlahBarang == 0 || jumlahBarang == "" || hargaBarang == 0 || hargaBarang == "" || keteranganBarang == "") {
      alert('Harap lengkapi data Barang untuk menambahkan ke keranjang');
      die;
    }
    //alert(jumlahBarang + hargaBarang+ keteranganBarang);
    else if (indexSama != null) {
      var jumlah = $('.cekJumlah:eq(' + indexSama + ')').val();
      $('.cekJumlah:eq(' + indexSama + ')').val(parseInt(jumlah) + parseInt(jumlahBarang));
      var keterangan = $('.cekKeterangan:eq(' + indexSama + ')').val();
      //$('.cekKeterangan:eq('+indexSama+')').val(keterangan + ".\n" +keteranganBarang);
      $('.cekKeterangan:eq(' + indexSama + ')').val(keteranganBarang);

      $('.keteranganVal:eq(' + indexSama + ')').html($('.cekKeterangan:eq(' + indexSama + ')').val());
      $('.jumlahVal:eq(' + indexSama + ')').html(($('.cekJumlah:eq(' + indexSama + ')').val()));
      $('.hargaVal:eq(' + indexSama + ')').html("Rp. " + ($('.cekJumlah:eq(' + indexSama + ')').val() * hargaBarang) + ',-');

      //var totalHargaKeranjang = $('#TotalHargaKeranjang').html().replace('.','');
      var totalHargaKeranjang = $('#TotalHargaKeranjang').attr('jumlahHarga').replace('.', '');
      //var totalHargaKeranjang = $('#TotalHargaKeranjang').val(parseInt(hargaBarang * jumlahBarang));
      //totalHargaKeranjang += parseFloat($('.cekJumlah:eq('+indexSama+')').val()) * parseFloat($("#hargaBarang").val());
      //totalHargaKeranjang = parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang);
      //alert(totalHargaKeranjang);
      //alert((totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang));
      //$('#TotalHargaKeranjang').html(formatRupiah(totalHargaKeranjang));
      //$('#TotalHargaKeranjang').val(totalHargaKeranjang);

      totalHarga = hargaBarang * jumlahBarang;
      $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalHargaKeranjang) + parseFloat(totalHarga));
      $('#TotalHargaKeranjang').html("Rp. " + formatRupiah($('#TotalHargaKeranjang').attr('jumlahHarga')));

      $("#barang").val("").change();
      $("#jumlahBarang").val(1);
      $("#hargaBarang").val(1);
      $("#keteranganBarang").val("");
      $('#hargaBarang').val(0);
      $('#tanpa-rupiah').val(0);
      $('#tanpa-rupiah').html(0);
      $('.selectpicker').selectpicker('refresh');

      //$('#TotalHargaKeranjang').val(parseInt(totalHargaKeranjang) + parseInt(hargaBarang * jumlahBarang));
      //$('#TotalHargaKeranjang').html(formatRupiah(parseInt(totalHargaKeranjang) + parseInt(hargaBarang * jumlahBarang)));




    } else {
      var htmlKeranjang = "";
      htmlKeranjang += '<li class="list-group-item d-flex justify-content-between lh-condensed">\n';
      htmlKeranjang += '<div>\n';
      htmlKeranjang += '<input type="hidden" class="cekId" name="itemId[]" value="' + idBarang + '">\n';
      htmlKeranjang += '<input type="hidden" class="cekJumlah" name="itemTotal[]" value="' + jumlahBarang + '">\n';
      htmlKeranjang += '<input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="' + keteranganBarang + '">\n';
      htmlKeranjang += '<input type="hidden" class="cekHarga" name="itemHarga[]" value="' + hargaBarang + '">\n';
      htmlKeranjang += '<h6 class="my-0">' + namaBarang + '<small class="jumlahVal" value="' + jumlahBarang + '">(' + jumlahBarang + ')</small> </h6>\n';
      htmlKeranjang += '<small class="text-muted keteranganVal" value="' + keteranganBarang + '">' + keteranganBarang + '</small><br>\n';
      htmlKeranjang += '</div>\n';
      htmlKeranjang += '<div>\n';
      htmlKeranjang += '<strong class="hargaVal" value="' + hargaBarang * jumlahBarang + '">Rp. ' + hargaBarang * jumlahBarang + ',-</strong>\n';
      htmlKeranjang += '<button class="btn btn-primary" type="button" id="copyKe">\n';
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

      var totalHargaKeranjang = $('#TotalHargaKeranjang').attr('jumlahHarga').replace('.', '');
      /*if(totalHargaKeranjang == "" || totalHargaKeranjang == NaN || totalHargaKeranjang == 0){
      alert(parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang));
        //totalHargaKeranjang = totalHargaKeranjang + parseFloat(hargaBarang * jumlahBarang);
        $('#TotalHargaKeranjang').html(formatRupiah(parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang)));
        $('#TotalHargaKeranjang').val(parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang));
      }
      else{
      alert("masuk siini di else  "+(parseFloat(totalHargaKeranjang)) + parseFloat(hargaBarang * jumlahBarang));
        //totalHargaKeranjang = parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang);
        $('#TotalHargaKeranjang').html(formatRupiah(parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang)));
        $('#TotalHargaKeranjang').val(parseFloat(totalHargaKeranjang) + parseFloat(hargaBarang * jumlahBarang));
      }*/
      totalHarga = hargaBarang * jumlahBarang;
      $('#TotalHargaKeranjang').attr('jumlahHarga', parseFloat(totalHargaKeranjang) + parseFloat(totalHarga));
      $('#TotalHargaKeranjang').html("Rp." + formatRupiah($('#TotalHargaKeranjang').attr('jumlahHarga')));
      //alert(totalHargaKeranjang);

      $("#barang").val("").change();
      $("#jumlahBarang").val(1);
      $("#hargaBarang").val(1);
      $("#keteranganBarang").val("");
      $('#hargaBarang').val(0);
      $('#tanpa-rupiah').val(0);
      $('#tanpa-rupiah').html(0);
      $('.selectpicker').selectpicker('refresh');
    }

  });

  $('body').on('click', '#copyKe', function() {
    //alert($(this).index('.copyKe'));
    var i = $(this).index('#copyKe');
    var idBarang = $('.cekId:eq(' + i + ')').val();
    //var namaBarang = $('.cekJumlah:eq('+i+')').val();
    var jumlahBarang = $('.cekJumlah:eq(' + i + ')').val();
    var hargaBarang = $('.cekHarga:eq(' + i + ')').val();
    var keteranganBarang = $('.cekKeterangan:eq(' + i + ')').val();

    //document.getElementById("barang").value = idBarang;
    /* var optionnya = '';
     var dataBarang = <?php echo json_encode($dataBarang); ?>;
     $.each(dataBarang, function( key, value ){
         if(value.ItemID.toString() == idBarang.toString()){
            optionnya += '<option selected id="namaBarang" value="'+value.ItemID+'">'+value.ItemName+'<nbsp>('+value.unitName+')</option>\n';          
         }
         else{
             optionnya += '<option id="namaBarang" value="'+value.ItemID+'">'+value.ItemName+'<nbsp>('+value.unitName+')</option>\n';      
                  
         }
     });*/
    //$("#barang").empty();
    $("#barang").val(idBarang).change();
    $('.selectpicker').selectpicker('refresh');

    $("#jumlahBarang").val(jumlahBarang);
    $("#hargaBarang").val(hargaBarang);
    $("#tanpa-rupiah").val(formatRupiah(hargaBarang));
    $("#keteranganBarang").val(keteranganBarang);

  });

  /*$("body").on("click", "#tambah", function() {
    totalTambah++;
    tambahCombo += '<div class="form-group p-3 mb-2 bg-light text-dark border" id="tmbhBarangJasa' + totalTambah + '">\n';
    tambahCombo += '<input type="hidden" name="totalRequest[]">\n';
    tambahCombo += '<div class="form-group" id="tmbhBarang">\n';
    tambahCombo += '<label for="title">Barang</label>\n';
    tambahCombo += '<select require name="barang[]" class="form-control" id="barang' + totalTambah + '">\n';
    tambahCombo += '<option value="">--Pilih barang--</option>\n';
    tambahCombo += '@foreach($dataBarang as $key => $data)\n';
    tambahCombo += '<option name="idBarang" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? '
    selected ' :'
    ' }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})  </option>\n';
    tambahCombo += '@endforeach\n';
    tambahCombo += '</select>\n';
    tambahCombo += '<input min=1 require name="jumlah[]" id="jml" type="number" class="form-control" placeholder="Jumlah barang" aria-label="Recipient' + "'" + 's username" aria-describedby="basic-addon2"id="angka" />\n';
    tambahCombo += '<br id="br">\n';
    tambahCombo += '</div>\n';
    tambahCombo += '<div class="form-group" id="harga' + totalTambah + '">\n';
    tambahCombo += '<label for="title">Harga</label>\n';
    tambahCombo += '<input require type="number" step=".01" id="tanpa-rupiah" name="harga[]" class="form-control">\n';
    tambahCombo += '</div>\n';
    tambahCombo += '<div class="form-group" id="ket' + totalTambah + '">\n';
    tambahCombo += '<label for="title">Keterangan</label>\n';
    tambahCombo += '<input require type="text" name="Keterangan[]" class="form-control" >\n';
    tambahCombo += '</div>\n';
    tambahCombo += '</div>';

    $('#totalRequest').append(tambahCombo);
    tambahCombo = "";
  });*/

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
    $('#hargaBarang').val(this.value.replace('.', '')); //aku nambah dewe buat simpen di hidden
    tanpa_rupiah.value = formatRupiah(this.value);
  });

  /* Dengan Rupiah */
  var dengan_rupiah = document.getElementById('dengan-rupiah');
  dengan_rupiah.addEventListener('keyup', function(e) {
    $('#hargaBarang').val(this.value.replace('.', '')); //aku nambah dewe buat simpen di hidden
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
</script>
@endsection