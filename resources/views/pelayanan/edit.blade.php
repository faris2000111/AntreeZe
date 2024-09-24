@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('pelayanan.update', $pelayanan->id_pelayanan) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Admin -->
                        <div class="col-12">
                            <label for="id_admin" class="form-label">Nama Admin</label>
                            <select name="id_admin" class="form-select" required>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id_admin }}" {{ $admin->id_admin == $pelayanan->id_admin ? 'selected' : '' }}>
                                        {{ $admin->nama_admin }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Nama admin harus dipilih!</div>
                        </div>

                        <!-- Jenis Pelayanan -->
                        <div class="col-12">
                            <label for="jenis" class="form-label">Jenis Pelayanan</label>
                            <input type="text" name="jenis" class="form-control" id="jenis" value="{{ $pelayanan->jenis }}" readonly required>
                            <div class="invalid-feedback">Jenis pelayanan tidak boleh kosong!</div>
                        </div>

                        <!-- No Pelayanan -->
                        <div class="col-12">
                            <label for="no_pelayanan" class="form-label">Nomor Pelayanan</label>
                            <input type="text" name="no_pelayanan" class="form-control" id="no_pelayanan" value="{{ $pelayanan->no_pelayanan }}" required>
                            <div class="invalid-feedback">Nomor pelayanan tidak boleh kosong!</div>
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

@section('scripts')
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

@endsection
