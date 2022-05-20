@extends('layouts.home_master')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif

@section('judul')
Kartu Stok
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item active">Kartu Stok</li>
@endsection


@section('content')
<div class="container-fluid">
    <h2 class="text-center display-4">Search Gudang</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="/kartuStok/searchLengkap/" method="get">
                <div class="input-group">
                    <select class="form-control selectpicker " data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idGudangTujuan" name="searchgudangid">
                        <option value="">
                            --Semua Gudang--
                        </option>
                        @foreach($dataGudang as $data)
                        @isset($gudang)
                        @if($gudang == $data->MGudangID)
                        <option selected name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endif
                        @endisset
                        <option name="idGudang" singkatan="{{$data->ccode}}" value="{{$data->MGudangID}}" {{$data->cname == $data->MGudangID? 'selected' :'' }}>{{$data->cname}}</option>
                        @endforeach

                    </select>
                    <select class="form-control selectpicker " data-live-search="true" data-show-subtext="true" style="width: 100%;" id="idItemSearch" name="searchitemid">
                        <option value="">
                            --Semua Barang--
                        </option>
                        @foreach($dataReportItem as $data)
                        @isset($gudang)
                        @isset($item)
                        @if($item == $data->ItemID && $data->MGudangID == $gudang)
                        <option selected id="itemid" value="{{$data->ItemID}}">{{$data->ItemName}}</option>
                        @elseif($data->MGudangID == $gudang)
                        <option id="itemid" value="{{$data->ItemID}}">{{$data->ItemName}}</option>
                        @endif
                        @endisset
                        @endisset
                        @endforeach

                    </select>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    @if(!empty($dateLengkap))
                    <input type="text" name="searchdateid" class="form-control float-right" id="reservation" value="{{old('tanggalDibutuhkan',$dateLengkap)}}">
                    @else
                    <input type="text" name="searchdateid" class="form-control float-right" id="reservation" value="{{old('tanggalDibutuhkan','')}}">
                    @endif
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kartu Stok</h3>
                    <a href="{{url('/kartuStok/searchLengkap/')}}" class="btn btn-primary btn-responsive float-right">Kartu Stok Lengkap
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                        </svg>
                    </a>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Tipe Transaksi</th>
                                <!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Item</th>
                                <th scope="col">Awal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($dataReport != null )
                            @php
                            $awal = 0;
                            $akhirStok = 0;
                            @endphp
                            @foreach($dataReport as $stok)
                            @if($loop->first)
                            @foreach($dataReportSingleSebelum as $stoksebelum)
                            <tr>
                                <td scope="col">{{ $loop->index + 1 }}</td>
                                @php
                                $awal = $awal + 1;
                                @endphp
                                <td scope="col">Data awal</td>
                                <td scope="col">Sebelum ({{date("d-m-Y", strtotime($stok->Date))}})</td>
                                <td scope="col">-</td>
                                <td scope="col">-</td>

                                <td>{{$stok->ItemName}}</td>
                                <td>{{$stoksebelum->totalQuantity}}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>{{$stoksebelum->totalQuantity}}</td>
                                @php
                                $akhirStok = $stoksebelum->totalQuantity;
                                @endphp

                            </tr>
                            @endforeach
                            @endif

                            <tr>
                                <td scope="col">{{ $loop->index + 1 + $awal }}</td>
                                <td scope="col">{{$stok->Name}}</td>
                                <td scope="col">{{date("d-m-Y", strtotime($stok->Date))}}</td>
                                <td scope="col">
                                    @if($stok->AdjustmentID != null)
                                    Penyesuaian Item
                                    @elseif($stok->SuratJalanID != null)
                                    Transaksi Menggunakan Surat Jalan
                                    @elseif($stok->StokAwalID != null)
                                    Stok Awal Gudang
                                    @else
                                    Transaksi
                                    @endif
                                </td>
                                <!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                <td scope="col">{{$stok->tipeTransaksi}}</td>

                                <td>{{$stok->ItemName}}</td>
                                <td>{{$akhirStok}}</td>
                                @if($stok->Quantity > 0)
                                <td>{{$stok->Quantity}}</td>
                                <td>0</td>
                                <td>{{$stok->Quantity+($akhirStok)}}</td>
                                @else
                                <td>0</td>
                                <td>{{abs($stok->Quantity)}}</td>
                                <td>{{$stok->Quantity+($akhirStok)}}</td>

                                @endif
                                @php
                                $akhirStok = $stok->Quantity+($akhirStok);
                                @endphp

                            </tr>
                            @endforeach

                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Tipe Transaksi</th>
                                <!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Item</th>
                                <th scope="col">Awal</th>
                                <th scope="col">Masuk</th>
                                <th scope="col">Keluar</th>
                                <th scope="col">Akhir</th>
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
    $(document).ready(function() {
        /*var id = $("#idGudangTujuan option:selected").val();
        var optionnya = '';
        var dataReportItem = <?php //echo json_encode($dataReportItem); ?>;
        var item = <?php //echo json_encode($item); ?>;
        if (item) {
            //alert(item);

            optionnya += '<option value="">--Pilih Barang--</option>\n';
            $.each(dataReportItem, function(key, value) {
                if (value.MGudangID.toString() == id.toString()) {
                    if(value.ItemID == item){
                        optionnya += '<option selected id="itemid" value="' + value.ItemID + '">' + value.ItemName + '</option>\n';

                    }
                    else{
                        optionnya += '<option id="itemid" value="' + value.ItemID + '">' + value.ItemName + '</option>\n';

                    }
                }
            });

            $("#idItemSearch").empty();
            $("#idItemSearch").append(optionnya);
            $('.selectpicker').selectpicker('refresh');
        }*/


        $("#idGudangTujuan").on("change", function() { //sudah
            var id = this.value;
            var optionnya = '';

            var dataReportItem = <?php echo json_encode($dataReportItem); ?>;

            //alert('masuk sini');
            optionnya += '<option value="" selected>--Pilih Barang--</option>\n';
            $.each(dataReportItem, function(key, value) {

                if (value.MGudangID.toString() == id.toString()) {
                    optionnya += '<option id="itemid" value="' + value.ItemID + '">' + value.ItemName + '</option>\n';
                }
            });

            $("#idItemSearch").empty();
            $("#idItemSearch").append(optionnya);

            $('.selectpicker').selectpicker('refresh');
        });
    });
</script>
@endsection