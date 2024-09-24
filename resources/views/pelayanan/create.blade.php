@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Form untuk menambah pelayanan -->
                    <form class="row g-3 needs-validation" action="{{ route('pelayanan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <!-- Dropdown Nama Admin -->
                        <div class="col-12">
                            <label for="id_admin" class="form-label">Nama Admin</label>
                            <select name="id_admin" class="form-control" id="id_admin" required>
                                <option value="" disabled selected>Pilih Admin</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Silakan pilih admin!</div>
                        </div>

                        <div class="col-12">
                            <label for="jenis" class="form-label">Jenis Pelayanan</label>
                            <select name="jenis" class="form-control" id="jenis" required>
                                <option value="" selected disabled>Pilih Jenis Pelayanan</option>
                                <option value="Loket">Loket</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Capster">Capster</option>
                            </select>
                            <div class="invalid-feedback">Jenis Pelayanan tidak boleh kosong!</div>
                        </div>

                        <div class="col-12">
                            <label for="no_pelayanan" class="form-label">No Pelayanan</label>
                            <input type="text" name="no_pelayanan" class="form-control" id="no_pelayanan" value="{{ $newNoPelayanan }}" readonly required>
                            <div class="invalid-feedback">No Pelayanan tidak boleh kosong!</div>
                        </div>



                        <div class="col-12">
                            <button class="btn w-100" style="color: white; background-color: {{ $profile->warna }};" type="submit">Tambah Pelayanan</button>
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
