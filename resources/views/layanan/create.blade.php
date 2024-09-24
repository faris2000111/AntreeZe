@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Form untuk menambah karyawan -->
                    <form class="row g-3 needs-validation" action="{{ route('layanan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="col-12">
                            <label for="nama_layanan" class="form-label">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" id="nama_layanan" required>
                            <div class="invalid-feedback">Please enter the service name!</div>
                        </div>

                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" required></textarea>
                            <div class="invalid-feedback">Please enter a description!</div>
                        </div>

                        <div class="col-12">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control" id="gambar" required>
                            <div class="invalid-feedback">Please upload a valid image!</div>
                        </div>

                        <div class="col-12">
                            <label for="waktu" class="form-label">Waktu</label>
                            <input type="time" name="waktu" class="form-control" id="waktu" required>
                            <div class="invalid-feedback">Silakan masukkan waktu!</div>
                        </div>

                        <div class="col-12">
                            <button class="btn w-100" style="color: white; background-color: {{ $profile->warna }};" type="submit">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 untuk error dan sukses -->
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

@endsection
