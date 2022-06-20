@extends('layouts.home_master')
<?php
//$currentUrl = Route::current()->getName();  //buat dapetno nama directory nya / route yang diapakek

?>
<style>
  p {
    font-family: 'Nunito', sans-serif;
  }
</style>
@section('content')

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
                    <th scope="col" colspan="4">
                      <h2> KARTU STOK BARANG</h2>
                    </th>
                    <th scope="col" colspan="3">
                      Perusahaan :{{$dataReportSingle[0]->perusahaanName}} <br><br>
                      Gudang :{{$dataReportSingle[0]->gudangName}}
                    </th>
                  </tr>
                </thead>
                <thead class="thead-light">
                  <tr>
                    <th scope="col" colspan="6" cellspacing="3">
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
                    <th scope="col">Tipe Transaksi</th>
                    <!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
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
                    </td>
                    <!--sg ada id surat jalan ya namanya surat jalan dan seterusnya-->
                    <td scope="col">{{$data->tipeTransaksi}}</td>
                    <td scope="col"> {{$data->Quantity}}</td>


                    <!--<td scope="col">{{abs($data->Quantity * $data->UnitPrice)}}</td>     -->

                  </tr>

                  @endforeach
                </tbody>
                <thead class="thead-light justify-content-center">
                  <tr>
                    <th scope="col" colspan="5">
                      <h3>Total</h3>
                    </th>
                    <th scope="col" colspan="1" id="hargaTotal"> {{$dataReportSingle[0]->totalQuantity}} </th>
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
  // window.addEventListener("load", window.print());
  $('#hargaTotal').html("Rp." + formatRupiah($('#hargaTotal').attr('hargaT')));
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