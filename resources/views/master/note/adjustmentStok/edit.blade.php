@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Perubahan Penyesuaian Stok Barang
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('adjustmentStock.index')}}">Penyesuaian Stok Barang</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{route('adjustmentStock.update', [$adjustmentStock->ItemAdjustmentID])}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="lastName">Name</label>
                    <input readonly type="text" class="form-control" id="tanggalDibuat" placeholder="" value="{{$adjustmentStock->Name}}">
                    <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
                </div>
                <div class="form-group">
                    <label for="lastName">Tanggal Pembuatan</label>
                    <input required name="tanggalDibuat" type="date" class="form-control" id="tanggalDibuat" placeholder="" required="" value="{{$adjustmentStock->Tanggal}}">
                    <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
                </div>



                <div class="form-group">
                    <label for="lastName">Pilih Gudang</label>
                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangID">
                        <option value="">
                            --Pilih Gudang--
                        </option>
                        @foreach($dataGudang as $key => $data)
                        @if($data->MGudangID == $adjustmentStockDetail[0]->MGudangID)
                        <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @else
                        <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endif
                        @endforeach

                    </select>
                </div>



                <div class="form-group">
                    <label for="lastName">Pilih Barang</label>
                    <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="ItemID" name="ItemID">
                        <option value="pilih">
                        @foreach($dataReport as $key => $data)
                        @if($data->MGudangID == $adjustmentStockDetail[0]->MGudangID && $data->ItemID == $adjustmentStockDetail[0]->ItemID)
                        <option selected value="{{$data->ItemID}}" {{ $data->ItemName == $data->ItemID ? "selected" :"" }} >{{$data->ItemName}} </option>
                        @else
                        <option value="{{$data->ItemID}}" {{ $data->ItemName == $data->ItemID ? "selected" :"" }} >{{$data->ItemName}} </option>
                        @endif
                        @endforeach
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Deskripsi</label>
                    <input required type="text" name="keterangan" class="form-control" value="{{old('jumlah',$adjustmentStock->Description)}}" maxlength="500">
                </div>

                <div class="form-group">
                    <label for="title">Jumlah Stok Barang Awal</label>
                    <input readonly type="number" step=".01" min="1" id="stokAwalBarang" name="QuantityAwal" class="form-control" value="{{old('QuantityAwal',$adjustmentStockDetail[0]->QuantityAwal)}}">
                </div>

                <div class="form-group">
                    <label for="title">Jumlah Stok Barang Baru</label>
                    <input required type="number" step=".01" min="1" name="QuantityBaru" class="form-control" value="{{old('QuantityBaru',$adjustmentStockDetail[0]->QuantityBaru)}}">
                </div>



            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {

            /*var id = $("#idGudang option:selected").val();
            var optionnya = '';
            var dataReport = <?php echo json_encode($dataReport); ?>;
            //var adjustmentStock = <?php echo json_encode($adjustmentStock); ?>;
            var adjustmentStockDetail = <?php echo json_encode($adjustmentStockDetail); ?>;
            optionnya += "<option value='pilih'>--Pilih Barang--</option>\n";
            $.each(dataReport, function(key, value) {

                if (value.MGudangID.toString() == id.toString()) {
                    if (value.ItemID.toString() == adjustmentStockDetail[0].ItemID.toString()) {
                        optionnya += '<option selected value="' + value.ItemID + '" {{' + value.ItemName + ' == ' + value.ItemID + '? "selected" :"" }} >' + value.ItemName + '</option>\n';
                    } else {
                        optionnya += '<option value="' + value.ItemID + '" {{' + value.ItemName + ' == ' + value.ItemID + '? "selected" :"" }} >' + value.ItemName + '</option>\n';
                    }

                }

            });


            $("#ItemID").empty();
            //let Barang = document.querySelector("#ItemID");
            //Barang.innerHTML = optionnya;
            $("#ItemID").append(optionnya);
            //alert(optionnya);
            $('.selectpicker').selectpicker('refresh');*/


            var idItem = $("#ItemID option:selected").val();
            var dataReportDetailStokAwal = <?php echo json_encode($dataReportDetailStokAwal); ?>;
            $.each(dataReportDetailStokAwal, function(key, value) {
                if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString()) {
                    $("#stokAwalBarang").val(value.totalQuantity);
                }
            });



            $("#idGudang").on("change", function() {

                var id = this.value;
                //alert(id);

                var optionnya = '';

                var dataReport = <?php echo json_encode($dataReport); ?>;

                //alert('masuk sini');
                optionnya += '<option value="pilih">--Pilih Barang--</option>\n';
                $.each(dataReport, function(key, value) {

                    if (value.MGudangID.toString() == id.toString()) {

                        optionnya += '<option value="' + value.ItemID + '">' + value.ItemName + '</option>\n';
                        //alert(optionnya);         
                    }
                });

                $("#ItemID").empty();
                $("#ItemID").append(optionnya);
                $('.selectpicker').selectpicker('refresh');
            });

            /*$("#ItemID").on("change",function(){     
            var id = this.value;
            if(id == "pilih" || id == ""){
                $("#stokAwalBarang").val("");     
            }
            else{
                var optionnya = '';
                
                var check = true;
                var dataReportDetailStokAwal = <?php //echo json_encode($dataReportDetailStokAwal); ?>;
                $.each(dataReportDetailStokAwal, function( key, value ){          
                    if(value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString()){
                        $("#stokAwalBarang").val(value.totalQuantity);     
                        check=false;
                    }
                });    

                if(check){ 
                    var dataReport = <?php //echo json_encode($dataReport); ?>;
                    $.each(dataReport, function( key, value ){          
                        if(value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString()){
                            $("#stokAwalBarang").val(value.totalQuantity);     
                        }
                    });   
                }
            }

        });

    });*/
            $("#ItemID").on("change", function() {
                var id = this.value;
                if (id == "pilih" || id == "") {
                    $("#stokAwalBarang").val("");

                } else {
                    var idGudang = $("#idGudang option:selected").val();
                    //alert(id);
                    var total = 0;
                    var datePembuatan = $("#tanggalDibuat").val();

                    var dataReportUntukStok = <?php echo json_encode($dataReportUntukStok); ?>;

                    $.each(dataReportUntukStok, function(key, value) {
                        if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString() && value.Date <= datePembuatan) {
                            //$("#stokAwalBarang").val(value.totalQuantity);     
                            total = total + parseFloat(value.Quantity);
                        }
                    });
                    $("#stokAwalBarang").val(total);

                }

            });

            $("#tanggalDibuat").on("change", function() {
                var id = $("#ItemID option:selected").val();
                if (id == "pilih" || id == "") {
                    $("#stokAwalBarang").val("");

                } else {
                    var idGudang = $("#idGudang option:selected").val();
                    //alert(id);
                    var total = 0;
                    var datePembuatan = $("#tanggalDibuat").val();
                    //alert(datePembuatan);
                    var dataReportUntukStok = <?php echo json_encode($dataReportUntukStok); ?>;

                    $.each(dataReportUntukStok, function(key, value) {
                        if (value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString() && value.Date <= datePembuatan) {
                            //$("#stokAwalBarang").val(value.totalQuantity);     
                            total = total + parseFloat(value.Quantity);
                        }
                    });
                    $("#stokAwalBarang").val(total);

                }

            });
        });
    </script>
    @endsection