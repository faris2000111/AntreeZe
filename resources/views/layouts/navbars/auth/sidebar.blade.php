@section('styles')
    <style>
        .navbar-vertical .navbar-nav > .nav-item .nav-link.active {
            background-color: {{ $profile->warna }};
        }
        .navbar-vertical .navbar-nav > .nav-item .nav-link.active .icon {
            background-image: linear-gradient(310deg, {{ $profile->warna }}, {{ $profile->warna }});
        }
    </style>
@endsection
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/img/' . $profile->logo) }}" class="navbar-brand-img h-100" alt="...">
        <span class="ms-3 font-weight-bold">{{$profile->nama_usaha}}</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse  w-auto" id="sidenav-collapse-main" style="flex-grow: 1; display: flex; flex-direction: column;">
    <ul class="navbar-nav flex-grow-1">

    <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('dashboard') ? 'active' : '') }}" href="{{ url('dashboard') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i style="font-size: 1rem;" class="fas fa-house-chimney ps-2 pe-2 text-center text-dark {{ (Request::is('dashboard') ? 'text-white' : 'text-dark') }}" aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('dashboard') ? 'text-white' : 'text-dark') }}">Dashboard</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('karyawan*') ? 'active' : '') }}" href="{{ url('karyawan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-address-card ps-2 pe-2 text-center text-dark {{ (Request::is('karyawan*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('karyawan*') ? 'text-white' : 'text-dark') }}">Karyawan</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('layanan*') ? 'active' : '') }}" href="{{ url('layanan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-boxes-stacked ps-2 pe-2 text-center text-dark {{ (Request::is('layanan*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('layanan*') ? 'text-white' : 'text-dark') }}">Layanan Produk</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('pelayanan*') ? 'active' : '') }}" href="{{ url('pelayanan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-shop-lock ps-2 pe-2 text-center text-dark {{ (Request::is('pelayanan*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('pelayanan*') ? 'text-white' : 'text-dark') }}">Pelayanan</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('booking*') ? 'active' : '') }}" href="{{ url('booking') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-cart-arrow-down ps-2 pe-2 text-center text-dark {{ (Request::is('booking*') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('booking*') ? 'text-white' : 'text-dark') }}">Booking</span>
        </a>
      </li>
      <li class="nav-item mt-2">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Laporan</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('laporan-mingguan') ? 'active' : '') }}" href="{{ url('laporan-mingguan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-chart-column ps-2 pe-2 text-center text-dark {{ (Request::is('laporan-mingguan') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan Mingguan</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('laporan-bulanan') ? 'active' : '') }}" href="{{ url('laporan-bulanan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-chart-bar ps-2 pe-2 text-center text-dark {{ (Request::is('laporan-bulanan') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan Bulanan</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('laporan-tahunan') ? 'active' : '') }}" href="{{ url('laporan-tahunan') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-chart-gantt ps-2 pe-2 text-center text-dark {{ (Request::is('laporan-tahunan') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan Tahunan</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Halaman Profil</h6>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link {{ (Request::is('profile') ? 'active' : '') }}" href="{{ url('profile') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-user ps-2 pe-2 text-center text-dark {{ (Request::is('profile') ? 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1 {{ (Request::is('profile') ? 'text-white' : 'text-dark') }}">Profil</span>
        </a>
      </li>
      <li class="nav-item pb-2">
        <a class="nav-link" href="{{ url('/logout') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i style="font-size: 1rem;" class="fas fa-lg fa-arrow-right-from-bracket ps-2 pe-2 text-center text-dark 'text-white' : 'text-dark') }} " aria-hidden="true"></i>
            </div>
            <span class="nav-link-text ms-1">Keluar</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
