@extends('layouts.user_type.auth')

@section('content')

<section class="section">
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">   
                <canvas id="myBarChart" width="400" height="200"></canvas>
                <script>
                    var ctx = document.getElementById('myBarChart').getContext('2d');
                    var myBarChart = new Chart(ctx, {
                        type: 'bar', // jenis chart
                        data: {
                            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], // Label pada sumbu X (Hari)
                            datasets: [
                                {
                                    label: 'Kepadatan Antrian', // Label dataset 1
                                    data: @json($chartData['antrian']), // Data kepadatan antrian per hari
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Warna bar biru
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Jam Booking', // Label dataset 2
                                    data: @json($chartData['booking']), // Data booking per hari
                                    backgroundColor: 'rgba(255, 206, 86, 0.7)', // Warna kuning
                                    borderColor: 'rgba(255, 206, 86, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Hari dalam Seminggu'
                                    }
                                },
                                y: {
                                    beginAtZero: true, // Memulai sumbu y dari 0
                                    title: {
                                        display: true,
                                        text: 'Jam Booking'
                                    },
                                    ticks: {
                                        callback: function(value, index, values) {
                                            // Menampilkan jam mulai dari jam buka hingga jam tutup
                                            return value + ':00';
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
        <div class="card-body">          

          <!-- Table with stripped rows -->
          <div class="table-responsive">
            <table class="table datatable" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr style="text-align: center;">
                  <th scope="col">No</th>
                  <th>No. Booking</th>
                  <th>Nama Layanan</th>
                  <th>Nama Pembeli</th>
                  <th>Jam Booking</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              @php
                  $no = 1;
              @endphp
                @foreach($booking as $bok)
                <tr style="text-align: center;">
                  <th scope="row">{{ $no++ }}</th>
                  <td>{{ $bok->nomor_booking }}</td>
                  <td>{{ $bok->nama_layanan }}</td>
                  <td>{{ $bok->nama_pembeli }}</td>
                  <td>{{ $bok->jam_booking }}</td>
                  <td>{{ $bok->tanggal }}</td>
                  <td>{{ $bok->status }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
