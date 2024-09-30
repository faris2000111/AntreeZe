@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('karyawan.update', $karyawan->id_admin) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama and Email in one row -->
                        <div class="col-md-6">
                            <label for="nama_admin" class="form-label">Nama</label>
                            <input type="text" name="nama_admin" class="form-control" id="nama_admin" value="{{ $karyawan->nama_admin }}" required>
                            <div class="invalid-feedback">Please, enter your name!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ $karyawan->email }}" required>
                            <div class="invalid-feedback">Please enter a valid Email address!</div>
                        </div>

                        <!-- Username and Password in one row -->
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" value="{{ $karyawan->username }}" required>
                            <div class="invalid-feedback">Please enter a valid Username!</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="number" name="no_hp" class="form-control" id="no_hp" value="{{ $karyawan->no_hp }}" required>
                            <div class="invalid-feedback">Please, enter your phone number!</div>
                        </div>
                        <!-- Confirm Password and No. HP in one row -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password (biarkan kosong jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div>

                        <!-- Submit button in full row -->
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
