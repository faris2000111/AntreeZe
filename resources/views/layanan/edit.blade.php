@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Nama Layanan dan Waktu in one row -->
                        <div class="col-md-6">
                            <label for="nama_layanan" class="form-label">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" id="nama_layanan" value="{{ $layanan->nama_layanan }}" required>
                            <div class="invalid-feedback">Nama layanan tidak boleh kosong!</div>
                        </div>

                        <!-- Deskripsi dan Gambar in one row -->
                        <div class="col-md-6">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi" required>{{ $layanan->deskripsi }}</textarea>
                            <div class="invalid-feedback">Deskripsi layanan tidak boleh kosong!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="gambar" class="form-label">Gambar (*jpg/jpeg/png/svg dan max 1MB)</label>
                            @if($layanan->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/layanan/' . $layanan->gambar) }}" alt="Gambar Layanan" style="max-width: 100px; height: auto;">
                                </div>
                            @endif
                            <input type="file" name="gambar" class="form-control" id="gambar">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="col-12">
                            <button class="btn w-100" style="color: white; background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }};" type="submit">Simpan</button>
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