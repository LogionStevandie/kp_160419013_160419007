<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('assets/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bs-stepper/css/bs-stepper.min.css')}}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{asset('assets/plugins/dropzone/min/dropzone.min.css')}}">
  <!-- Theme style-->
  <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
  <!-- Toastr  -->
  <link rel="stylesheet" href="{{asset('assets/plugins/toastr/toastr.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<!-- script comboboc-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" >
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css%22%3E">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" ></script> 

  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" ></script>

</head>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
                <div class="row">
                    <div class="col-12 table-responsive">
                      <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col" colspan="3"><h2>SURAT JALAN</h2></th>
                                        <th scope="col" colspan="2">
                                            Tanggal pembuatan : {{date("d-m-Y", strtotime($suratJalan->tanggalDibuat))}}<br>
                                            @foreach($dataGudang as $key => $data)
                                                @if($data->MGudangID == $suratJalan->MGudangIDAwal)
                                                    Gudang awal :{{$data->cname}}<br>
                                                @endif
                                            @endforeach

                                            @foreach($dataGudang as $key => $data)
                                                @if($data->MGudangID == $suratJalan->MGudangIDTujuan)
                                                    Gudang Tujuan :{{$data->cname}}<br>
                                                @endif
                                            @endforeach
                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col"colspan="6"cellspacing="3" >

                                         @foreach($dataPurchaseRequest as $key => $data)
                                            @if($data->MGudangID == $suratJalan->MGudangIDTujuan)
                                                  @if($data->id == $suratJalan->PurchaseRequestID)
                                                        Data Permintaan pembelian :{{$data->name}} - {{date("d-m-Y", strtotime($data->tanggalDibuat))}}<br>
                                                  @endif
                                            @endif
                                          @endforeach


                                        </th>
                                        </tr>
                                    </thead>
                                      <thead class="thead-light">
                                        <tr>
                                        <th scope="col"colspan="6"cellspacing="2" >
                                          Keterangan kendaraan:  <span style="white-space: pre-line">{{$suratJalan->keteranganKendaraan}} </span><br>
                                          Keterangan nomor polisi:  <span style="white-space: pre-line">{{$suratJalan->keteranganNomorPolisi}}</span> <br>
                                          Keterangan pemudi:  <span style="white-space: pre-line">{{$suratJalan->keteranganPemudi}}</span> <br>
                                          Keterangan transaksi:  <span style="white-space: pre-line">{{$suratJalan->keteranganTransaksi}}</span><br>
                                          Keterangan Gudang Tujuan:  <span style="white-space: pre-line">{{$suratJalan->keteranganGudangTujuan}}</span><br>
                                          Keterangan Penerima:  <span style="white-space: pre-line">{{$suratJalan->keteranganPenerima}}</span>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataTotalDetail as $data)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{$data->itemName}}</td>
                                                <td>{{$data->jumlah}}</td>
                                                <td class="text-right">@php echo "Rp " . number_format($data->harga,2,',','.'); @endphp</td>
                                                <td> <span style="white-space: pre-line">{{$data->keterangan}}</span></td>
                                            </tr>
                                        @endforeach  
                                    </tbody>

                        </table>
                     
                       
                    </div>
                  <!-- /.col -->
                </div>
           

              
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
<script type="text/javascript">
  window.addEventListener("load", window.print());
$('#hargaTotal').html("Rp." +formatRupiah($('#hargaTotal').attr('hargaT')));
//$('#hargaY').html("Rp." +formatRupiah($('#hargaY').attr('harga')));
//$('#hargaZ').html("Rp." +formatRupiah($('#hargaZ').attr('hargaTasd')));
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

