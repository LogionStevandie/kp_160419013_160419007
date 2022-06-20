<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/home" class="brand-link">
    <img src="{{asset('assets/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">NeeDed</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a class="d-block text-white">{{ Auth::user()->name }}</a>
        <a class="d-block text-white">{{ Auth::user()->role->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-header">KARTU STOK</li>
        @foreach(Auth::user()->role->roleAccess as $key)
        @if($key->Url == "inventoryTransaction")
        <li class="nav-item">
          <a href="{{route('inventoryTransaction.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Stok Barang di Gudang
            </p>
          </a>
        </li>
        @endif
        @endforeach

        @foreach(Auth::user()->role->roleAccess as $key)
        @if($key->Url == "stokAwal.index")
        <li class="nav-item">
          <a href="{{route('stokAwal.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Stok Barang Awal di Gudang
            </p>
          </a>
        </li>
        @endif
        @endforeach
        <li class="nav-header">PEMBUATAN NOTA</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>Nota
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "purchaseRequest.index")
            <li class="nav-item">
              <a href="{{route('purchaseRequest.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Permintaan Pembelian
                </p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "purchaseOrder.index")
            <li class="nav-item">
              <a href="{{route('purchaseOrder.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Purchase Order
                </p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "terimaBarangSupplier.index")
            <li class="nav-item">
              <a href="{{route('terimaBarangSupplier.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Terima Barang Supplier
                </p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "suratJalan.index")
            <li class="nav-item">
              <a href="{{route('suratJalan.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Surat Jalan
                </p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "kirimBarangPesanan.index")
            <li class="nav-item">
              <a href="{{route('kirimBarangPesanan.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Kirim Pesanan
                </p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "terimaBarangPesanan.index")
            <li class="nav-item">
              <a href="{{route('terimaBarangPesanan.index')}}" class="nav-link">
                <i class="nav-icon fas fa-columns"></i>
                <p>
                  Terima Pesanan
                </p>
              </a>
            </li>
            @endif
            @endforeach

          </ul>
        </li>







        <!--Permintaan-->
        <li class="nav-header">PERSETUJUAN</li>
        @foreach(Auth::user()->role->roleAccess as $key)
        @if($key->Url == "approvedPurchaseRequest.index")
        <li class="nav-item">
          <a href="{{route('approvedPurchaseRequest.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Permintaan Pembelian
            </p>
          </a>
        </li>
        @endif
        @endforeach
        @foreach(Auth::user()->role->roleAccess as $key)
        @if($key->Url == "approvedPurchaseOrder.index")
        <li class="nav-item">
          <a href="{{route('approvedPurchaseOrder.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Purchase Order
            </p>
          </a>
        </li>
        @endif
        @endforeach
        <!-- END Permintaan-->

        <!--
        <li class="nav-header">CEK SELESAI NOTA</li>
        <li class="nav-item">
          <a href="{{route('checkPurchaseRequest.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Permintaan Pembelian
            </p>
          </a>

        </li>
        <li class="nav-item">
          <a href="{{route('checkPurchaseOrder.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Purchase Order
            </p>
          </a>
        </li>
        -->

        <!--Permintaan-->
        <li class="nav-header">PENYESUAIAN STOK BARANG</li>
        @foreach(Auth::user()->role->roleAccess as $key)
        @if($key->Url == "adjustmentStock.index")
        <li class="nav-item">
          <a href="{{route('adjustmentStock.index')}}" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              Penyesuaian Stok
            </p>
          </a>
        </li>
        @endif
        @endforeach

        <!-- END Permintaan-->

        <li class="nav-header">MASTER</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Lokasi
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "mPulau.index")
            <li class="nav-item">
              <a href="{{route('mPulau.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pulau</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "mProvinsi.index")
            <li class="nav-item">
              <a href="{{route('mProvinsi.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Provinsi</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "mKota.index")
            <li class="nav-item">
              <a href="{{route('mKota.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Kota</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "mPerusahaan.index")
            <li class="nav-item">
              <a href="{{route('mPerusahaan.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Perusahaan</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "mGudang.index")
            <li class="nav-item">
              <a href="{{route('mGudang.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Gudang</p>
              </a>
            </li>
            @endif
            @endforeach
            <!-- <li class="nav-item">
              <a href="{{route('mGudangAreaSimpan.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Gudang Area Simpan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('tagValuesMGudang.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tag Values Gudang</p>
              </a>
            </li>-->
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Item
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "item.index")
            <li class="nav-item">
              <a href="{{route('item.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>item</p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "itemCategory.index")
            <li class="nav-item">
              <a href="{{route('itemCategory.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Kategori item</p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "itemTag.index")
            <li class="nav-item">
              <a href="{{route('itemTag.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tag item</p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "itemTransaction.index")
            <li class="nav-item">
              <a href="{{route('itemTransaction.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p> item Transaksi</p>
              </a>
            </li>
            @endif
            @endforeach


            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "unit.index")
            <li class="nav-item">
              <a href="{{route('unit.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Satuan item</p>
              </a>
            </li>
            @endif
            @endforeach

            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "itemTagValues.index")
            <li class="nav-item">
              <a href="{{route('itemTagValues.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tag Values Item</p>
              </a>
            </li>
            @endif
            @endforeach


          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Pajak
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "tax.index")
            <li class="nav-item">
              <a href="{{route('tax.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>tax</p>
              </a>
            </li>
            @endif
            @endforeach

          </ul>
        </li>



        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Supplier
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "msupplier.index")
            <li class="nav-item">
              <a href="{{route('msupplier.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>list Supplier</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "infoSupplier.index")
            <li class="nav-item">
              <a href="{{route('infoSupplier.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Info Supplier</p>
              </a>
            </li>
            @endif
            @endforeach

          </ul>
        </li>

        <!--Payment-->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Pembayaran
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "payment.index")
            <li class="nav-item">
              <a href="{{route('payment.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Jenis Pembayaran</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "paymentTerms.index")
            <li class="nav-item">
              <a href="{{route('paymentTerms.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Ketentuan Pembayaran</p>
              </a>
            </li>
            @endif
            @endforeach

          </ul>
        </li>
        <!--End Payment-->




        <!--Menu-->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Menu & User Role
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "menu.index")
            <li class="nav-item">
              <a href="{{route('menu.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Menu</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "users.index")
            <li class="nav-item">
              <a href="{{route('users.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "role.index")
            <li class="nav-item">
              <a href="{{route('role.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Role</p>
              </a>
            </li>
            @endif
            @endforeach
            @foreach(Auth::user()->role->roleAccess as $key)
            @if($key->Url == "roleAccess.index")
            <li class="nav-item">
              <a href="{{route('roleAccess.index')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Akses Role</p>
              </a>
            </li>
            @endif
            @endforeach
          </ul>

          <!--RoleAccess place-->

        </li>
        <!--End Menu-->



      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>