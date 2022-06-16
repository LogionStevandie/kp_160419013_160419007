@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Edit Terima Barang Supplier
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('terimaBarangSupplier.index')}}">Terima Barang Supplier</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form action="{{route('terimaBarangSupplier.update',[$transactionGudangBarang->id])}}" method="POST" >
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
                                  <input readonly name="tanggalDibuat" type="date" class="form-control" id="lastName" placeholder="" value="{{$transactionGudangBarang->tanggalDibuat}}" required="">
                                  <div class="invalid-feedback"> Valid last name is required. </div>
                              </div>
                              <div class="col-md-6 mb-3"> 
                                  <label for="lastName">Tanggal Datang</label>
                                  <input name="tanggalDatang" type="date" class="form-control" id="lastName" placeholder="" value="{{$transactionGudangBarang->tanggalDatang}}" required="">
                                  <div class="invalid-feedback"> Valid last name is required. </div>
                              </div>

                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="lastName">Pilih Gudang Penerima</label> 
                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangIDTujuan">
                                      <option value="">
                                            --Pilih Gudang--
                                        </option>
                                        @foreach($dataGudang as $key => $data)
                                            @if($data->MGudangID == $transactionGudangBarang->MGudangIDTujuan)
                                                <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                            @else
                                                <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                                            @endif
                                        @endforeach
                                
                                    </select>
                                    </div>
                                 
                              </div>

                              <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="lastName">Pilih Supplier</label> 
                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idSupplierDipilih" name="Supplier">
                                      <option value=""> 
                                            --Pilih Supplier--
                                        </option>
                                        @foreach($dataSupplier as $key => $data)
                                            @if($data->SupplierID == $transactionGudangBarang->SupplierID)
                                                <option selected name="idSupplier" value="{{$data->SupplierID}}"{{$data->Name == $data->SupplierID? 'selected' :'' }}>{{$data->Name}}</option>
                                            @else
                                                <option name="idSupplier" value="{{$data->SupplierID}}"{{$data->Name == $data->SupplierID? 'selected' :'' }}>{{$data->Name}}</option>
                                            @endif
                                        @endforeach
                                
                                    </select>
                                    </div>
                                 
                              </div>
                           
                              <div class="col-md-6">
                                    <div class="form-group">
                                   <label for="lastName">Jenis Transaksi</label> 
                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;"name="ItemTransaction" readonly>
                                      <option value="">
                                          --Pilih Jenis Transaksi--
                                      </option>
                                    @foreach($dataItemTransaction as $key => $data)
                                        @if($data->ItemTransactionID == $transactionGudangBarang->ItemTransactionID)
                                            <option selected name="idItemTransaction" value="{{$data->ItemTransactionID}}"{{$data->Name == $data->ItemTransactionID? 'selected' :'' }}>{{$data->Name}}</option>
                                        @else
                                            <option name="idItemTransaction" value="{{$data->ItemTransactionID}}"{{$data->Name == $data->ItemTransactionID? 'selected' :'' }}>{{$data->Name}}</option>
                                        @endif
                                    @endforeach
                                
                                    </select>
                                    </div>
                                 
                               </div>

                               <div class="col-md-6">
                                    <div class="form-group">
                                   <label for="lastName">Data Purchase Order</label> 
                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;"name="poID" id="idPurchaseOrder">
                                    <option value=""> 
                                          --Pilih Purchase Order--
                                      </option>
                                     @foreach($dataPurchaseOrder as $key => $data)
                                        @if($data->idSupplier == $transactionGudangBarang->SupplierID)
                                            @if($data->id == $transactionGudangBarang->PurchaseOrderID)
                                             <option selected name="idPO" value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}-({{$data->tanggalDibuat}})</option>
                                            @else
                                             <option name="idPO" value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}-({{$data->tanggalDibuat}})</option>
                                            @endif
                                        @endif
                                    @endforeach
                                      <!--<option value=""> 
                                          --Pilih Purchase Order--
                                      </option>
                                      @foreach($dataSupplier as $key => $data)
                                      @endforeach
                                      @foreach($dataPurchaseOrder as $key => $data)
                                          <option name="idPO" value="{{$data->id}}"{{$data->name == $data->id? 'selected' :'' }}>{{$data->name}}-({{$data->tanggalDibuat}})</option>
                                      @endforeach  -->
                                    </select>
                                    </div>
                               </div>
                                        

                               <div class="col-md-6 mb-3">
                                  <label for="lastName">Keterangan Kendaraan</label>
                                  <textarea rows="3"  type="text" name="keteranganKendaraan" class="form-control" value="{{old('keteranganKendaraan',$transactionGudangBarang->keteranganKendaraan)}}" >{{$transactionGudangBarang->keteranganKendaraan}}</textarea>
                              </div>

                              <div class="col-md-6 mb-3">
                                  <label for="lastName">Keterangan Nomor Polisi</label>
                                  <textarea rows="3"  type="text" name="keteranganNomorPolisi" class="form-control" value="{{old('keteranganNomorPolisi',$transactionGudangBarang->keteranganNomorPolisi)}}" >{{$transactionGudangBarang->keteranganNomorPolisi}}</textarea>
                              </div>

                              <div class="col-md-6 mb-3">
                                  <label for="lastName">Keterangan Pemudi</label>
                                  <textarea rows="3"  type="text" name="keteranganPemudi" class="form-control" value="{{old('keteranganPemudi',$transactionGudangBarang->keteranganPemudi)}}" >{{$transactionGudangBarang->keteranganPemudi}}</textarea>
                              </div>

                              <div class="col-md-6 mb-3">
                                  <label for="lastName">Keterangan Transaksi</label>
                                  <textarea rows="3"  type="text" name="keteranganTransaksi" class="form-control" value="{{old('keteranganTransaksi',$transactionGudangBarang->keteranganTransaksi)}}" >{{$transactionGudangBarang->keteranganTransaksi}}</textarea>
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
                             
                                 <div class="form-group"  id='tmbhBarang'>
                                    <label for="title">Barang</label>
                                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;"name="barang" id="barang">
                                  
                                        <option value="pilih">--Pilih barang--</option>
                                        <!--@foreach($dataBarang as $key => $data)
                                        <option id="namaBarang" value="{{$data->ItemID}}"{{$data->ItemName == $data->ItemID? 'selected' :'' }}>{{$data->ItemName}}<nbsp>({{$data->unitName}})</option>
                                        @endforeach-->
                                    </select>
                                    <input id="jumlahBarang" value="1" min="1"  type="number" step=".01" class="form-control" placeholder="Jumlah barang" aria-label="Recipient's username" aria-describedby="basic-addon2" />
                                </div>

                                    <div class="form-group " id="ket">
                                        <label for="Keterangan">Keterangan</label>
                                        <textarea rows="3" id="keteranganBarang" class="form-control" value="{{old('keterangan','')}}" ></textarea>
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
                                          <span class="badge badge-secondary badge-pill" name="totalBarangnya" id="totalBarangnya" value={{count($dataTotalDetail)}}>{{count($dataTotalDetail)}}</span>
                                      </h4>
                                      <ul class="list-group mb-3 sticky-top" id="keranjang">
                                        @foreach($dataTotalDetail as $data)
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div id="hiddenDiv">
                                                <input type="hidden" class="cekId" name="itemId[]" value="{{$data->ItemID}}">
                                                <input type="hidden" id="cekJumlah" class="cekJumlah" name="itemJumlah[]" value="{{$data->jumlah}}">
                                                <input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="{{$data->keterangan}}">
                                                <input type="hidden" class="cekHarga" name="itemHarga[]" value="{{$data->hargaPOD}}">
                                                <input type="hidden" class="cekPod" name="podID[]" value="{{$data->idPOD}}">
                                                <h6 class="my-0">{{$data->itemName}}<small class="jumlahVal" value="'+jumlahBarang+'">{{$data->jumlah}}</small> </h6>
                                                <small class="text-muted keteranganVal" value="'+keteranganBarang+'">{{$data->keterangan}}</small><br>
                                            </div>
                                            <div>
                                                <button class="btn btn-primary copyKe" type="button" id="copyKe">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                </svg>
                                                </button>
                                                <button class="btn btn-danger" type="button" id="hapusKeranjang">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
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
                                  </div>  <!---->
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


   

    $(document).ready(function(){
       /* var id =$("#idSupplierDipilih option:selected").val();
        
        var optionnya = '';
        var dataPurchaseOrder = <?php echo json_encode($dataPurchaseOrder); ?>;
        var dataTransaksi = <?php echo json_encode($transactionGudangBarang); ?>;
        optionnya += '<option value="pilih" selected>--Pilih Purchase Order--</option>\n';
        $.each(dataPurchaseOrder, function( key, value ){
        
            if(value.idSupplier.toString() == id.toString()){
                //alert('masuk'); 
                if(dataTransaksi.PurchaseOrderID == value.id){
                    optionnya += '<option selected id="idPO" value="'+value.id+'">'+value.name+'-('+value.tanggalDibuat+')</option>\n';
                       
                }
                else{
                    optionnya += '<option id="idPO" value="'+value.id+'">'+value.name+'-('+value.tanggalDibuat+')</option>\n';        
                }
            }
        });
                            
        $("#idPurchaseOrder").empty();
        $("#idPurchaseOrder").append(optionnya);*/
        

        var id = $("#idPurchaseOrder option:selected").val();
        var optionnya = '';

        var dataPurchaseOrderDetail = <?php echo json_encode($dataPurchaseOrderDetail); ?>;

        //alert('masuk sini');
        optionnya += '<option value="pilih" selected>--Pilih Barang--</option>\n';
        $.each(dataPurchaseOrderDetail, function( key, value ){
            
            if(value.idPurchaseOrder.toString() == id.toString()){
                optionnya += '<option id="namaBarang" namaBarang='+value.ItemName +' harga='+ value.harga +' idPodId='+ value.id +' value="'+value.idItem+'">'+value.ItemName+'<nbsp>('+value.UnitName+')</option>\n';      
            }
        });
                            
        $("#barang").empty();
        $("#barang").append(optionnya);


        $('.selectpicker').selectpicker('refresh');

        $("#idSupplierDipilih").on("change",function(){  //sudah
                
                var id = this.value;
                //alert(id);
                //var singkatan = $("#perusahaanID option:selected").attr('singkatan');
                //alert(singkatan);
                var optionnya = '';
            
                var dataPurchaseOrder = <?php echo json_encode($dataPurchaseOrder); ?>;
    
                //alert('masuk sini');
                optionnya += '<option value="pilih" selected>--Pilih Purchase Order--</option>\n';
                $.each(dataPurchaseOrder, function( key, value ){
                   
                    if(value.idSupplier.toString() == id.toString()){
                        //alert('masuk'); 
                        optionnya += '<option id="idPO" value="'+value.id+'">'+value.name+'-('+value.tanggalDibuat+')</option>\n';
                        //alert(optionnya);         
                    }
                });
            
                                    
                $("#idPurchaseOrder").empty();
                $("#idPurchaseOrder").append(optionnya);
                $('.selectpicker').selectpicker('refresh');
            });

        $("#idPurchaseOrder").on("change",function(){  //sudah
                
            var id = this.value;
            //alert(id);
            //var singkatan = $("#perusahaanID option:selected").attr('singkatan');
            //alert(singkatan);
            var optionnya = '';
        
            var dataPurchaseOrderDetail = <?php echo json_encode($dataPurchaseOrderDetail); ?>;

            //alert('masuk sini');
            optionnya += '<option value="pilih" selected>--Pilih Barang--</option>\n';
            $.each(dataPurchaseOrderDetail, function( key, value ){
               
                if(value.idPurchaseOrder.toString() == id.toString()){
                    //alert('masuk'); 
                    //alert("masuk cek");
                    optionnya += '<option id="namaBarang" namaBarang='+value.ItemName +' harga='+ value.harga +' idPodId='+ value.id +' value="'+value.idItem+'">'+value.ItemName+'<nbsp>('+value.UnitName+')</option>\n';
                    //alert(optionnya);         
                }
            });
        
                                
            $("#barang").empty();
            $("#barang").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
        });

        $("#barang").on("change",function(){  //sudah
                
                var id = this.value;
                var idPodID = $("#barang option:selected").attr('idPodId');
                var optionnya = '';
                var maxAngka = 0;
                var dataPurchaseOrderDetail = <?php echo json_encode($dataPurchaseOrderDetail); ?>;
                var dataDetail = <?php echo json_encode($dataTotalDetail); ?>;
    
                $.each(dataPurchaseOrderDetail, function( key, value ){
                    if(value.id.toString() == idPodID.toString() && value.idItem.toString() == id.toString()){
                        maxAngka = parseFloat(value.jumlah) - parseFloat(value.jumlahProses);

                        $.each(dataDetail, function( k, v ){
                            if(v.purchaseOrderDetailID.toString() == value.id.toString() && value.ItemID.toString() == v.ItemID.toString()){
                                //alert("masuk minus");
                                maxAngka -= parseFloat(v.jumlah);
                            }
                        });
                        $.each($('.cekPod'), function(idx, val) {
                            if(val.value == value.id){
                                var jumlahBarang = $('.cekJumlah:eq('+idx+')').val();
                                maxAngka = maxAngka - jumlahBarang;
                            }
                        });
                        //alert(maxAngka);
                        $("#jumlahBarang").attr({
                            "max" : maxAngka,        
                            "min" : 1,
                            "placeholder" : "Jumlah Barang (Maksimal: " + maxAngka + ")",       
                            "value" : "",   
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

     $('body').on('click','#copyKe', function(){ //belum
        //alert($(this).index('.copyKe'));
        var i = $(this).index('#copyKe');
        
        var idBarang = $('.cekId:eq('+i+')').val();
        var jumlahBarang = $('.cekJumlah:eq('+i+')').val();

        var hargaBarang = $('.cekHarga:eq('+i+')').val();
        var keteranganBarang = $('.cekKeterangan:eq('+i+')').val();
        var diskonBarang = $('.cekDiskon:eq('+i+')').val();

        $("#barang").val(idBarang);
        $("#jumlahBarang").val(jumlahBarang);
        $("#keteranganBarang").val(keteranganBarang);

        //$('.selectpicker').selectpicker('refresh');
        
    });



    $('body').on('click','#hapusKeranjang', function(){
        //alert($('.cekId:eq(2)').val());
        //alert($('.cekId').length);cekJumlah
        var jumlah = $(this).parent().parent().children("#hiddenDiv").children(".cekJumlah").val();
        //alert(jumlah);
        $("#jumlahBarang").attr({
            "max" : parseFloat($("#jumlahBarang").attr("max")) + parseFloat(jumlah),        
            "min" : 1,
            "placeholder" : "Jumlah Barang (Maksimal: " + (parseFloat($("#jumlahBarang").attr("max")) + parseFloat(jumlah)) + ")",       
            "value" : "",         
        }); 
        $(this).parent().parent().remove();
        var totalSekarang = $('#totalBarangnya').attr("value");
        totalSekarang -= 1
        $('#totalBarangnya').val(totalSekarang);
        $('#totalBarangnya').html(totalSekarang);
    });

    $('body').on('click','#tambahKeranjang', function(){
        
        var idBarang = $("#barang").val();//
        var namaBarang = $("#barang option:selected").html();//
        var idpodID = $("#barang option:selected").attr("idPodId");
        var hargaBarang = $("#barang option:selected").attr("harga");
        var jumlahBarang = parseFloat($("#jumlahBarang").val());//
        var keteranganBarang = $("#keteranganBarang").val();//

        var indexSama = null;
        for(let i=0;i<$('.cekId').length;i++){
            if($('.cekId:eq('+i+')').val() == idBarang){
                if($('.cekPod:eq('+i+')').val() == idpodID){
                    indexSama = i;
                }
            }
        }
        
        if(idBarang == "" || namaBarang == "--Pilih Barang--" || jumlahBarang <= 0 || jumlahBarang.toString() == "NaN" || jumlahBarang == null || keteranganBarang == ""){
            alert('Harap lengkapi atau isi data Barang dengan benar');
            die;
        }
         else if (jumlahBarang > $("#jumlahBarang").attr("max"))
        {
                $('#jumlahBarang').val(0);
                alert("harap masukkan jumlah barang yang sesuai");
        }
        else if(indexSama != null){
            //alert("masuk indexSama");
            var jumlah = $('.cekJumlah:eq('+indexSama+')').val();
            $('.cekJumlah:eq('+indexSama+')').val(parseFloat(jumlah) + parseFloat(jumlahBarang));
            var keterangan = $('.cekKeterangan:eq('+indexSama+')').val();
            $('.cekKeterangan:eq('+indexSama+')').val(keterangan + ".\n" +keteranganBarang);
            
            $('.keteranganVal:eq('+indexSama+')').html($('.cekKeterangan:eq('+indexSama+')').val());
            $('.jumlahVal:eq('+indexSama+')').html(($('.cekJumlah:eq('+indexSama+')').val()));

            var maxAngka = parseFloat($("#jumlahBarang").attr("max")) - parseFloat(jumlahBarang);
        }
        else{
            //alert("masuk");
            var htmlKeranjang = "";
            htmlKeranjang += '<li class="list-group-item d-flex justify-content-between lh-condensed">\n';
            htmlKeranjang += '<div id="hiddenDiv">\n';
            htmlKeranjang += '<input type="hidden" class="cekId" name="itemId[]" value="'+idBarang+'">\n';
            htmlKeranjang += '<input type="hidden" id="cekJumlah" class="cekJumlah" name="itemJumlah[]" value="'+jumlahBarang+'">\n';
            htmlKeranjang += '<input type="hidden" class="cekKeterangan" name="itemKeterangan[]" value="'+keteranganBarang+'">\n';
            htmlKeranjang += '<input type="hidden" class="cekHarga" name="itemHarga[]" value="'+hargaBarang+'">\n';
            htmlKeranjang += '<input type="hidden" class="cekPod" name="podID[]" value="'+idpodID+'">\n';
            htmlKeranjang += '<h6 class="my-0">'+ namaBarang +'<small class="jumlahVal" value="'+jumlahBarang+'">('+jumlahBarang+')</small> </h6>\n';
            htmlKeranjang += '<small class="text-muted keteranganVal" value="'+keteranganBarang+'">'+keteranganBarang+'</small><br>\n';
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
            var totalSekarang = $('#totalBarangnya').attr("value");
            totalSekarang += 1
            $('#totalBarangnya').val(totalSekarang);
            $('#totalBarangnya').html(totalSekarang);

        }
        $("#barang").val("").change(); //
        $("#jumlahBarang").val(""); //
        $("#keteranganBarang").val(""); //

    });

    /* Tanpa Rupiah */
    var tanpa_rupiah = document.getElementById('tanpa-rupiah');
    tanpa_rupiah.addEventListener('keyup', function(e)
    {
        $('#hargaBarang').val(this.value.toString().replace(/\./g, ''));
        //alert(this.value.toString().replace(/\./g, ''));
        tanpa_rupiah.value = formatRupiah(this.value);
    });

    var tanpa_rupiah_diskon = document.getElementById('tanpa-rupiah-diskon');
    tanpa_rupiah_diskon.addEventListener('keyup', function(e)
    {
        $('#diskonBarang').val(this.value.toString().replace(/\./g, ''));
        tanpa_rupiah_diskon.value = formatRupiah(this.value);
    });

    /* Dengan Rupiah */
    var dengan_rupiah = document.getElementById('dengan-rupiah');
    dengan_rupiah.addEventListener('keyup', function(e)
    {
        $('#hargaBarang').val(this.value.toString().replace(/\./g, ''));
        dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
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

    $(document).ready(function(){
        $('input[name=jenis0]').click(function(){

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


