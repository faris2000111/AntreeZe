@extends('layouts.user_type.auth')

@section('content')

<section class="section">
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">   
                <div class="card-header pb-0">
                    <h4 style="text-align:center;">Chart Mingguan</h4>
                </div>
                <canvas id="myBarChart" width="400" height="200"></canvas>
                <script>
                    // Ambil context canvas
                    var ctx = document.getElementById('myBarChart').getContext('2d');

                    // Buat chart menggunakan Chart.js
                    var myBarChart = new Chart(ctx, {
                        type: 'bar', // Menggunakan chart jenis 'bar'
                        data: {
                            labels: @json($days), // Label untuk sumbu X (Hari: Senin hingga Minggu)
                            datasets: @json($chartData) // Dataset yang sudah dikirim dari controller, berisi layanan dan jumlah booking per hari
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: false, // Bar tidak ditumpuk, melainkan berdampingan
                                    title: {
                                        display: true,
                                        text: 'Hari dalam Seminggu' // Judul untuk sumbu X
                                    }
                                },
                                y: {
                                    beginAtZero: true, // Mulai dari 0 pada sumbu Y
                                    stacked: false, // Bar tidak ditumpuk di sumbu Y
                                    title: {
                                        display: true,
                                        text: 'Jumlah Booking' // Judul untuk sumbu Y
                                    },
                                    ticks: {
                                        stepSize: 1 // Mengatur agar chart menampilkan booking per 1 unit
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true // Menampilkan legenda untuk setiap layanan
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card">
            <div class="card-body"> 
                <div class="card-header pb-0">
                    <h4 style="text-align:center;">Booking Mingguan</h4>
                </div>         
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
                            <tr style="text-align: center;" class="{{ $bok->status == 'selesai' ? 'table-success' : ($bok->status == 'dibatalkan' ? 'table-danger' : '') }}">
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
            </div>
        </div>
    </div>
                <!-- End of table -->
                <div class="col-lg-12 mt-4">
                    <div class="card">
                        <div class="card-body">  
                            <div class="card-header pb-0">
                                <h4 style="text-align:center;">Pengguna Baru Mingguan</h4>
                              </div>        
                            <div class="table-responsive">
                                <table class="table datatable" id="dataTable2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th scope="col">No</th>
                                            <th>Nama Pembeli</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th>No. HP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach($users as $user)
                                        <tr style="text-align: center;">
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $user->nama_pembeli }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End of table -->
            </div>
        </div>
    </div>
  </div>
</section>
  <script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "pagingType": "simple",
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-arrow-left'></i>", 
                "next": "<i class='fas fa-arrow-right'></i>" 
            }
        }
    });
    
    $('#dataTable2').DataTable({
        "pagingType": "simple",
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-arrow-left'></i>", 
                "next": "<i class='fas fa-arrow-right'></i>" 
            }
        }
    });
});
</script>

@endsection
