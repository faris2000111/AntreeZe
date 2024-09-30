@extends('layouts.user_type.auth')

@section('content')

  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Booking</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $totalBookingBaru }}
                <span class="text-sm font-weight-bolder {{ $persentasePerubahan1 >= 0 ? 'text-success' : 'text-danger' }}">
                  {{ $persentasePerubahan1 }}%
                </span>
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="fa-solid fa-cart-plus text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Booking Berhasil</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $totalBookingBerhasil }}
                <span class="text-sm font-weight-bolder {{ $persentasePerubahan2 >= 0 ? 'text-success' : 'text-danger' }}">
                {{ $persentasePerubahan2 }}%
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="fa-solid fa-cart-shopping text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengguna Baru</p>
              <h5 class="font-weight-bolder mb-0">
                {{ $totalPenggunaBaru }}
                <span class="text-sm font-weight-bolder {{ $persentasePerubahan >= 0 ? 'text-success' : 'text-danger' }}">
                  {{ $persentasePerubahan }}%
                </span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
              <i class="fa-solid fa-user-plus text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Karyawan</p>
                <h5 class="font-weight-bolder mb-0">
                {{ $totalKaryawan }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-lg-6">
              <div class="d-flex flex-column h-100">
                <p class="mb-1 pt-2 text-bold">Selamat Datang</p>
                <h5 class="font-weight-bolder">{{$admin->nama_admin}}</h5>
                <p class="mb-5">Pantau laporan penjualan, booking harian, dan pantau usaha Anda dengan mudah melalui dashboard ini.</p>
                <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="javascript:;">
                  Ubah Profil
                  <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
              <div class="bg-gradient-info border-radius-lg h-100">
                <img src="../assets/img/shapes/waves-white.svg" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                  <img class="w-100 position-relative z-index-2 pt-4" src="../assets/img/illustrations/rocket-white.png" alt="rocket">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card h-100 p-3">
        <div class="card-body d-flex flex-column h-100">
          <h5>Performa Bulanan</h5>

          <p class="text-xs mb-1">Total Booking Bulan Ini: <strong>{{ $totalBookings }}</strong></p>
          <p class="text-xs mb-1">Booking Berhasil: <strong>{{ $successfulBookings }}</strong></p>
          <p class="text-xs mb-1">Booking Dibatalkan: <strong>{{ $cancelledBookings }}</strong></p>
          
          <p class="text-xs mb-1">Layanan Terlaris: 
            <strong>{{ $popularServiceName ?? 'Tidak ada data' }}</strong>
          </p>

          <a class="text-body text-sm font-weight-bold mt-auto" href="#">
            Baca Selengkapnya
            <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>

  <div class="my-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
      <div class="card">
        <div class="card-header pb-0">
            <div class="col-lg-6 col-7">
              <h6>Projects</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-check text-info" aria-hidden="true"></i>
                <span class="font-weight-bold ms-1">30 done</span> this month
              </p>
            </div>
            <div class="col-lg-6 col-5 my-auto text-end">
              <div class="dropdown float-lg-end pe-4">
                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-ellipsis-v text-secondary"></i>
                </a>
                <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                  <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                  <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                  <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                </ul>
              </div>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Layanan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Penjualan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage of Total</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Completion</th>
                </tr>

              </thead>
              <tbody>
              @foreach($popularServices as $service)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="../assets/img/small-logos/logo-xd.svg" class="avatar avatar-sm me-3" alt="{{ $service->nama_layanan }}">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $service->nama_layanan }}</h6> <!-- Dynamic service name -->
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="avatar-group mt-2">
                      <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Sales Count">
                        <img src="../assets/img/team-1.jpg" alt="sales_count">
                      </a>
                    </div>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="text-xs font-weight-bold"> {{ $service->total }} </span> <!-- Dynamic sales count -->
                  </td>
                  <td class="align-middle">
                    <div class="progress-wrapper w-75 mx-auto">
                      <div class="progress-info">
                        <div class="progress-percentage">
                          <span class="text-xs font-weight-bold">{{ ($service->total / $totalBookings) * 100 }}%</span> <!-- Percentage calculation -->
                        </div>
                      </div>
                      <div class="progress">
                        <div class="progress-bar bg-gradient-info w-{{ round(($service->total / $totalBookings) * 100) }}" role="progressbar" aria-valuenow="{{ round(($service->total / $totalBookings) * 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach

                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if(session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif

@endsection
@section('scripts')
  @if(session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        title: 'Login Berhasil!',
        text: 'Selamat datang {{$admin->nama_admin}}!',
        icon: 'success',
        confirmButtonText: 'OK'
      });
    </script>
  @endif
@endsection