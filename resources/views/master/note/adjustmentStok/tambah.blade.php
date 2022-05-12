@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Pembuatan Penyesuaian Stok Barang  
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('adjustmentStock.index')}}">Stok Awal Barang</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('adjustmentStock.store')}}" method="POST" >
        @csrf
        <div class="card-body">
            <div class="form-group"> 
                <label for="lastName">Tanggal Pembuatan</label>
                <input required name="tanggalDibuat" type="date" class="form-control" id="tanggalDibuat" placeholder="" required="" value="{{date('Y-m-d')}}">
                <!--<div class="invalid-feedback"> Valid last name is required. </div>-->
            </div>

           

            <div class="form-group">
                <label for="lastName">Pilih Gudang</label> 
                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudang" name="MGudangID">
                    <option value="">
                        --Pilih Gudang--
                    </option>
                    @foreach($dataGudang as $key => $data)
                        <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}"{{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                    @endforeach
            
                </select>
            </div>



            <div class="form-group">
                <label for="lastName">Pilih Barang</label> 
                <select class="form-control selectpicker" data-live-search="true" data-show-subtext="true" style="width: 100%;" id="ItemID" name="ItemID">
                    
                </select>
            </div>

              <div class="form-group">
                <label for="title">Deskripsi</label>
                <input required  type="text"  name="keterangan" class="form-control" 
                value="{{old('jumlah','')}}" maxlength="500" >
            </div>

            <div class="form-group">
                <label for="title">Jumlah Stok Barang Awal</label>
                <input readonly  type="number" step=".01" min="1" id="stokAwalBarang" name="QuantityAwal" class="form-control" 
                value="{{old('QuantityAwal','')}}">
            </div>

            <div class="form-group">
                <label for="title">Jumlah Stok Barang Baru</label>
                <input required  type="number" step=".01" min="1"  name="QuantityBaru" class="form-control" 
                value="{{old('QuantityBaru','')}}" >
            </div>
            
       

        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>

    $(document).ready(function(){

        $("#idGudang").on("change",function(){
                
            var id = this.value;
            //alert(id);
            var optionnya = '';
        
            var dataReport = <?php echo json_encode($dataReport); ?>;

            //alert('masuk sini');
            optionnya += '<option value="pilih" selected>--Pilih Barang--</option>\n';
            $.each(dataReport, function( key, value ){
               
                if(value.MGudangID.toString() == id.toString()){

                    optionnya += '<option value="'+value.ItemID+'">'+value.ItemName+'</option>\n';      
                    //alert(optionnya);         
                }
            });

            $("#ItemID").empty();
            $("#ItemID").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
        });

        $("#ItemID").on("change",function(){     
            var id = this.value;
            if(id == "pilih" || id == ""){
                $("#stokAwalBarang").val("");     
                
            }
            else{
                var idGudang = $("#idGudang option:selected").val();
                //alert(id);
                var optionnya = '';
            
                var dataReport = <?php echo json_encode($dataReport); ?>;

                $.each(dataReport, function( key, value ){          
                    if(value.ItemID.toString() == id.toString() && value.MGudangID.toString() == idGudang.toString()){
                        $("#stokAwalBarang").val(value.totalQuantity);     
                    }
                });    
            }
            
        });

    });

</script>
@endsection   


