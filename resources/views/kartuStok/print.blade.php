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
              <!-- title -->
                <div class="row">
                    <div class="col-12 table-responsive">
                      <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col" colspan="4"><h2> KARTU STOK BARANG</h2></th>
                                        <th scope="col" colspan="3">
                                              Perusahaan :{{$dataReportSingle[0]->perusahaanName}}  <br><br>
                                              Gudang :{{$dataReportSingle[0]->gudangName}} 
                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col"colspan="6"cellspacing="3" >
                                            Nama Barang :{{$dataReportSingle[0]->namaBarang}}<br><br>
                                            Satuan : {{$dataReportSingle[0]->satuan}} <br>  
                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Transaksi</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Tipe Transaksi</th><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                            <th scope="col">Jenis Transaksi</th>
                                            <th scope="col">Jumlah</th>
                                            <!--<th scope="col">Total Harga</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dataReport as $data)
                                      
                                      <tr>
                                          
                                        <td scope="col">{{ $loop->index + 1 }}</td>
                                        <td scope="col">{{$data->Name}}</td>
                                        <td scope="col">{{date("d-m-Y", strtotime($data->Date))}}</td>
                                        <td scope="col">
                                          @if($data->AdjustmentID != null)
                                            Penyesuaian Item
                                          @elseif($data->SuratJalanID != null)
                                            Transaksi Menggunakan Surat Jalan
                                          @elseif($data->StokAwalID != null)
                                            Stok Awal Gudang
                                          @else
                                            Transaksi
                                          @endif
                                        </td><!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                                        <td scope="col">{{$data->tipeTransaksi}}</td>
                                        <td scope="col"> {{$data->Quantity}}</td>
                                         
                                        
                                        <!--<td scope="col">{{abs($data->Quantity * $data->UnitPrice)}}</td>     -->                                 
                                          
                                      </tr>
                                    
                                    @endforeach
                                    </tbody>
                                    <thead class="thead-light justify-content-center">
                                        <tr>
                                            <th scope="col" colspan="5" > <h3>Total</h3> </th>
                                            <th scope="col" colspan="1"  id="hargaTotal" > {{$dataReportSingle[0]->totalQuantity}} </th>
                                             <!--<th scope="col" colspan="1"  id="hargaTotal" > {{$dataReportSingle[0]->totalQuantity}} </th>-->
                                        </tr>
                                    </thead>
                             
                           

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
 // window.addEventListener("load", window.print());
$('#hargaTotal').html("Rp." +formatRupiah($('#hargaTotal').attr('hargaT')));
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

