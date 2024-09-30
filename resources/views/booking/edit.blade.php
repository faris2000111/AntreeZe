@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Form now submits directly to the update method -->
                    <form id="bookingForm" class="row g-3 needs-validation" action="{{ route('booking.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Nomor Booking and No. Loket in one row -->
                        <div class="col-md-6">
                            <label for="nomor_booking" class="form-label">Nomor Booking</label>
                            <input type="text" name="nomor_booking" class="form-control" id="nomor_booking" value="{{ $booking->nomor_booking }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="no_loket" class="form-label">No. Loket</label>
                            <input type="text" name="no_loket" class="form-control" id="no_loket" value="{{ $booking->jenis }} {{ $booking->no_pelayanan }}" readonly required>
                            <div class="invalid-feedback">Masukkan No. Loket yang valid!</div>
                        </div>

                        <!-- Nama Pembeli and No. HP in one row -->
                        <div class="col-md-6">
                            <label for="id_users" class="form-label">Nama Pembeli</label>
                            <input type="text" name="id_users" class="form-control" id="nama_pembeli" value="{{ $booking->nama_pembeli }}" readonly required>
                            <div class="invalid-feedback">Masukkan Nama Pembeli yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" id="no_hp" value="{{ $booking->phone_number }}" readonly required>
                            <div class="invalid-feedback">Masukkan nomor HP yang valid!</div>
                        </div>

                        <!-- Layanan and Jam Booking in one row -->
                        <div class="col-md-6">
                            <label for="layanan" class="form-label">Layanan</label>
                            <input type="text" name="layanan" class="form-control" id="layanan" value="{{ $booking->nama_layanan }}" readonly required>
                            <div class="invalid-feedback">Masukkan layanan yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="jam_booking" class="form-label">Jam Booking</label>
                            <input type="text" name="jam_booking" class="form-control" id="jam_booking" value="{{ $booking->jam_booking }}" readonly required>
                            <div class="invalid-feedback">Masukkan jam booking yang valid!</div>
                        </div>

                        <!-- Tanggal and Catatan in one row -->
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" id="tanggal" value="{{ $booking->tanggal }}" readonly required>
                            <div class="invalid-feedback">Masukkan tanggal yang valid!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" id="catatan" rows="3">{{ $booking->catatan }}</textarea>
                        </div>

                        <!-- Buttons in one row with same size and small gaps -->
                        <div class="col-12 d-flex gap-1 justify-content-start">
                            <div class="col">
                                <button type="submit" name="action" value="dibatalkan" class="btn w-100" style="background-color: white; border: 2px solid {{ $profile->warna }}; color: {{ $profile->warna }};">Dibatalkan</button>
                            </div>
                            <div class="col">
                                <button type="submit" name="action" value="lewati" class="btn w-100" style="background-color: white; border: 2px solid {{ $profile->warna }}; color: {{ $profile->warna }};">Lewati</button>
                            </div>
                            <div class="col">
                                <button type="submit" name="action" value="selanjutnya" class="btn w-100" style="background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }}; color: white; ">Selanjutnya</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 for Success Message -->
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000
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

@endsection
