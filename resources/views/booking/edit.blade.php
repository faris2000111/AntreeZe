@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Form Pencarian -->
                    <div class="mb-4">
                        <label for="nomor_booking_search" class="form-label">Cari Nomor Booking</label>
                        <input type="text" name="nomor_booking" class="form-control" id="nomor_booking_search" placeholder="Masukkan Nomor Booking" required>
                    </div>

                    <!-- Form Booking -->
                    <form id="bookingForm" class="row g-3 needs-validation" action="{{ route('booking.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="nomor_booking" class="form-label">Nomor Booking</label>
                            <input type="text" name="nomor_booking" class="form-control" id="nomor_booking" value="{{ $booking->nomor_booking ?? '' }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="id_users" class="form-label">Nama Pembeli</label>
                            <input type="text" name="id_users" class="form-control" id="nama_pembeli" value="{{ $booking->nama_pembeli ?? '' }}" readonly required>
                            <div class="invalid-feedback">Masukkan Nama Pembeli yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" id="no_hp" value="{{ $booking->phone_number ?? '' }}" readonly required>
                            <div class="invalid-feedback">Masukkan nomor HP yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="layanan" class="form-label">Layanan</label>
                            <input type="text" name="layanan" class="form-control" id="layanan" value="{{ $booking->nama_layanan ?? '' }}" readonly required>
                            <div class="invalid-feedback">Masukkan layanan yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="jam_booking" class="form-label">Jam Booking</label>
                            <input type="text" name="jam_booking" class="form-control" id="jam_booking" value="{{ $booking->jam_booking ?? '' }}" readonly required>
                            <div class="invalid-feedback">Masukkan jam booking yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal" value="{{ $booking->tanggal ?? '' }}" readonly required>
                            <div class="invalid-feedback">Masukkan tanggal yang valid!</div>
                        </div>
                        
                        <div class="col-12 d-flex gap-1 justify-content-start">
                            <div class="col">
                                <button type="submit" name="action" value="dibatalkan" class="btn btn-cancel w-100" style="background-color: white; border: 2px solid {{ $profile->warna }}; color: {{ $profile->warna }};">Dibatalkan</button>
                            </div>
                            <div class="col">
                                <button type="submit" name="action" value="lewati" class="btn btn-lewati w-100" style="background-color: white; border: 2px solid {{ $profile->warna }}; color: {{ $profile->warna }};">Lewati</button>
                            </div>
                            <div class="col">
                                @if ($booking->status == 'dipesan')
                                    <button type="submit" name="action" value="selanjutnya" class="btn btn-selanjutnya w-100" style="background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }}; color: white;">Proses</button>
                                @elseif ($booking->status == 'diproses')
                                    <button type="submit" name="action" value="selesai" class="btn btn-selesai w-100" style="background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }}; color: white;" id="selesaiButton">Selesai</button>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000
        }).then(() => {
            let nextQueueNumber = "{{ session('nextQueueNumber') }}";
            let nextBuyerName = "{{ session('nextBuyerName') }}"; 
            const action = "{{ session('action') }}";

            if (['selesai', 'dibatalkan', 'lewati'].includes(action) && 'speechSynthesis' in window && nextQueueNumber && nextBuyerName) {
                window.speechSynthesis.cancel();

                let message;
                if (action === 'selesai') {
                    message = `Nomor antrian selanjutnya adalah ${nextQueueNumber}, atas nama ${nextBuyerName}.`;
                } else if (action === 'dibatalkan') {
                    message = `Antrian sebelumnya telah dibatalkan. Nomor antrian selanjutnya adalah ${nextQueueNumber}, atas nama ${nextBuyerName}.`;
                } else if (action === 'lewati') {
                    message = `Antrian sebelumnya telah dilewati. Nomor antrian selanjutnya adalah ${nextQueueNumber}, atas nama ${nextBuyerName}.`;
                } 

                let utterance1 = new SpeechSynthesisUtterance(message);
                utterance1.lang = 'id-ID';
                window.speechSynthesis.speak(utterance1);

                setTimeout(() => {
                    let utterance2 = new SpeechSynthesisUtterance(message);
                    utterance2.lang = 'id-ID';
                    window.speechSynthesis.speak(utterance2);
                }, 2000); 
            }
        });
    </script>
@endif


@if ($errors->any())
    <script>
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessages,
            confirmButtonText: 'Ok'
        });
    </script>
@endif

<script>
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); 
    
            let action = this.value;
            let message = '';
    
            switch (action) {
                case 'dibatalkan':
                    message = 'Apakah kamu yakin ingin membatalkan booking ini?';
                    break;
                case 'lewati':
                    message = 'Apakah kamu yakin ingin melewati booking ini?';
                    break;
                case 'selanjutnya':
                    message = 'Apakah kamu yakin ingin memproses booking selanjutnya?';
                    break;
                case 'selesai':
                    message = 'Apakah kamu yakin booking ini sudah selesai?';
                    break;
            }
    
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = action; 
                    document.getElementById('bookingForm').appendChild(actionInput);
    
                    document.getElementById('bookingForm').submit();
                }
            });
        });
    });
</script>

<script>
let isSearching = false; // Variabel untuk mendeteksi status pencarian

function fetchBookingData() {
    // Pastikan hanya fetch otomatis jika tidak ada pencarian aktif
    if (isSearching) return;

    // Ambil data booking secara otomatis
    fetch(`/booking/fetch`)
        .then(response => response.json())
        .then(data => {
            if (data.booking) {
                updateBookingFields(data.booking);
            } else {
                clearFields();
            }
        })
        .catch(error => {
            console.error('Error fetching booking data:', error);
            clearFields();
        });
}

function clearFields() {
    document.getElementById('nomor_booking').value = '';
    document.getElementById('nama_pembeli').value = '';
    document.getElementById('no_hp').value = '';
    document.getElementById('layanan').value = '';
    document.getElementById('jam_booking').value = '';
    document.getElementById('tanggal').value = '';
}

function updateBookingFields(booking) {
    document.getElementById('nomor_booking').value = booking.nomor_booking;
    document.getElementById('nama_pembeli').value = booking.nama_pembeli;
    document.getElementById('no_hp').value = booking.phone_number;
    document.getElementById('layanan').value = booking.nama_layanan;
    document.getElementById('jam_booking').value = booking.jam_booking;
    document.getElementById('tanggal').value = booking.tanggal;
}

// Fungsi untuk pencarian booking
document.getElementById('nomor_booking_search').addEventListener('input', function() {
    let nomorBooking = this.value;

    // Saat input mulai dilakukan (pencarian aktif)
    if (nomorBooking.length >= 1) {
        isSearching = true; // Pencarian aktif

        fetch(`/booking/fetch?nomor_booking=${nomorBooking}`)
            .then(response => response.json())
            .then(data => {
                if (data.booking) {
                    updateBookingFields(data.booking); // Tampilkan hasil pencarian
                } else {
                    clearFields(); // Jika tidak ditemukan
                }
            })
            .catch(error => {
                console.error('Error fetching booking:', error);
            });

    } else {
        // Jika input kosong, kembali ke pencarian otomatis
        isSearching = false;
        // Hanya fetch otomatis jika tidak ada pencarian aktif
        fetchBookingData();
    }
});

// Set Interval untuk fetch otomatis jika tidak ada pencarian aktif
let fetchInterval = setInterval(() => {
    if (!isSearching) { // Hanya fetch otomatis jika tidak ada pencarian aktif
        fetchBookingData();
    }
}, 10000);

// Menghentikan interval fetch otomatis saat pencarian aktif
document.getElementById('nomor_booking_search').addEventListener('focus', function () {
    clearInterval(fetchInterval); // Hentikan interval ketika fokus pada input pencarian
});

// Menyalakan interval kembali setelah pencarian selesai (blur)
document.getElementById('nomor_booking_search').addEventListener('blur', function () {
    fetchInterval = setInterval(() => {
        if (!isSearching) { // Pastikan hanya fetch otomatis jika tidak ada pencarian aktif
            fetchBookingData();
        }
    }, 10000); // Mulai lagi interval ketika focus hilang
});
</script>

@endsection