@extends('layouts.user_type.auth')

@section('content')

<style>
  .custom-flex-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Center content horizontally */
    gap: 20px; /* Add some space between the cards */
  }

  .custom-flex-grid .card-container {
    flex: 1 1 calc(33.33% - 20px); /* 3 per row, minus gap */
    max-width: calc(33.33% - 20px); /* Prevent overflow */
    min-width: 300px; /* Set a minimum width */
    box-sizing: border-box; /* Ensure padding and borders are included in the width */
  }

  @media (max-width: 768px) {
    .custom-flex-grid .card-container {
      flex: 1 1 calc(50% - 20px); /* 2 per row on smaller screens */
      max-width: calc(50% - 20px);
    }
  }

  @media (max-width: 576px) {
    .custom-flex-grid .card-container {
      flex: 1 1 100%; /* 1 per row on extra small screens */
      max-width: 100%;
    }
  }
</style>

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
                      {{ $persentasePerubahan1 !== null ? number_format($persentasePerubahan1, 2) . '%' : 'Tidak ada data' }}
                  </span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape shadow text-center border-radius-md" style="background-color:{{ $profile->warna }};">
              <i class="fa-solid fa-cart-plus text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Berhasil -->
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
                {{ number_format($persentasePerubahan2, 2) }}%
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape shadow text-center border-radius-md" style="background-color:{{ $profile->warna }};">
              <i class="fa-solid fa-cart-shopping text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pengguna Baru -->
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
                  {{ number_format($persentasePerubahan, 2) }}%
                </span>
              </h5>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape shadow text-center border-radius-md" style="background-color:{{ $profile->warna }};">
              <i class="fa-solid fa-user-plus text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Karyawan -->
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
            <div class="icon icon-shape shadow text-center border-radius-md" style="background-color:{{ $profile->warna }};">
              <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Performa Bulanan -->
<div class="row mt-4">
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100">
              <p class="mb-1 pt-2 text-bold">Selamat Datang</p>
              <h5 class="font-weight-bolder">{{$admins->nama_admin}}</h5>
              <p class="mb-5">Pantau laporan penjualan, booking harian, dan pantau usaha Anda dengan mudah melalui dashboard ini.</p>
              <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="/profile">
                Ubah Profil
                <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
            <div class="border-radius-lg h-100" style="background-color:{{ $profile->warna }};">
              <img src="../assets/img/shapes/waves-white.svg" class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
              <div class="position-relative d-flex align-items-center justify-content-center h-100">
                <img class="w-100 position-relative z-index-2 pt-4" src="{{ $admins->avatar }}" alt="Avatar {{ $admins->nama_admin }}">
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
        <a class="text-body text-sm font-weight-bold mt-auto" href="/laporan-bulanan">
          Baca Selengkapnya
          <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header pb-0">
        @if($adminPelayanan)
        <h4 style="text-align:center;">Antrian {{ $admin->jenis }}</h4>
        @else
        <h4 style="text-align:center;">Admin belum terdaftar di halaman pelayanan</h4>
      </div>
      @endif
      <div class="card-body">
        <div class="custom-flex-grid" id="loket-container">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="my-4">
  <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        @if($adminPelayanan)
          <h4 style="text-align:center;">Chart Booking Hari Ini {{ $admin->jenis }} {{ $admin->no_pelayanan }} </h4>
        @else
          <h4 style="text-align:center;">Admin belum terdaftar di halaman pelayanan</h4>
        @endif
      </div>
      <div class="card-body">
        @if($adminPelayanan)
          <canvas id="hourlyBookingChart" width="400" height="200"></canvas>
          <script>
            var ctx = document.getElementById('hourlyBookingChart').getContext('2d');
            var chartLabels = @json($chartLabels);
            var chartData = @json($chartData);
          
            var hourlyBookingChart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: chartLabels,
                datasets: chartData
              },
              options: {
                responsive: true,
                scales: {
                  x: {
                    title: {
                      display: true,
                      text: 'Jam Booking'
                    }
                  },
                  y: {
                    beginAtZero: true,
                    title: {
                      display: true,
                      text: 'Jumlah Booking'
                    },
                    ticks: {
                      stepSize: 1,
                      precision: 0
                    }
                  }
                }
              }
            });
          </script>
        @else
          <p style="text-align:center; color: red;">Tidak ada data untuk ditampilkan.</p>
        @endif
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
      text: 'Selamat datang {{$admins->nama_admin}}!',
      icon: 'success',
      confirmButtonText: 'OK'
    });
  </script>
@endif

@endsection

@push('scripts')
<script>
    function fetchLoketData() {
        fetch(`/check-loket`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                var loketContainer = document.getElementById('loket-container');
                loketContainer.innerHTML = '';

                if (data.lokets && data.lokets.length > 0) {
                    data.lokets.forEach(loket => {
                        var row = `
                            <div class="card-container">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Loket ${loket.no_pelayanan}</h5>
                                        <p class="card-text">Nomor Booking: ${loket.nomor_booking ? loket.nomor_booking : 'Tidak ada'}</p>
                                        <p class="card-text">Jam Booking: ${loket.jam_booking ? loket.jam_booking : 'Tidak ada'}</p>
                                    </div>
                                </div>
                            </div>`;
                        loketContainer.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    loketContainer.innerHTML = '<p>Tidak ada data loket tersedia.</p>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    fetchLoketData();
    setInterval(fetchLoketData, 3000); 
</script>
@endpush


