

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form method="POST" >
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col" colspan="3"><h2>PERSETUJUAN PERMINTAAN PEMBELIAN</h2></th>
                                        <th scope="col" colspan="3">
                                            Nama Npp : {{$purchaseRequest->name}}<br>
                                            Tanggal pembuatan : {{date("d-m-Y", strtotime($purchaseRequest->tanggalDibuat))}}

                                        </th>
                                        </tr>
                                    </thead>
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col"colspan="6"cellspacing="3" >
                                                                            
                                        @foreach($dataGudang as $data)
                                            @if($data->MGudangID == $purchaseRequest->MGudangID)
                                            Gudang :{{$data->cname}} <nbsp> ({{$purchaseRequest->MGudangID}}) <br> 
                                            @endif 
                                        @endforeach
                                        Jenis permintaan : {{$purchaseRequest->jenisProses}} <br>
                                        Tanggal dibutuhkan : {{date("d-m-Y", strtotime($purchaseRequest->tanggalDibutuhkan))}}<br>
                                        Tanggal batas akhir : {{date("d-m-Y", strtotime($purchaseRequest->tanggalAkhirDibutuhkan))}}
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
                                            <th scope="col">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                            @foreach($prd as $data) 
                                            <tr>
                                                @if($data->idPurchaseRequest==$purchaseRequest->id)
                                                <th scope="row">{{$data->id}}</th>
                                                <th scope="row">{{$data->ItemName}}</th>
                                                <td>{{$data->jumlah}}</td>
                                                <td>{{$data->harga}}</td>              
                                                <td>{{$data->keterangan_jasa}}</td>                                          
                                                <td>{{$data->jumlah * $data->harga}}</td>                                          
                                                @endif
                                            </tr>
                                            @endforeach
                                    
                                    </tbody>
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" colspan="5"> <h3>Total</h3> </th>
                                            <th scope="col" colspan="5"  id="hargaTotal" hargaT="{{$purchaseRequest->totalHarga}}"> {{$purchaseRequest->totalHarga}} </th>
                                        
                                        </tr>
                                    </thead>
                                    
                                </table>

                            </div>
                        
                            <div class="form-group">
                  

            </div>
        
           
        </form>
    </div>
</div>

<script type="text/javascript">
  window.addEventListener("load", window.print());
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
