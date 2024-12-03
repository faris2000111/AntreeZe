@extends('layouts.user_type.auth')

@section('content')

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">

                    <form class="row g-3 needs-validation" action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        
                        <!-- Nama and Email in one row -->
                        <div class="col-md-6">
                            <label for="nama_admin" class="form-label">Nama</label>
                            <input type="text" name="nama_admin" class="form-control" id="nama_admin" required>
                            <div class="invalid-feedback">Please, enter your name!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                            <div class="invalid-feedback">Please enter a valid Email address!</div>
                        </div>

                        <!-- Username and Password in one row -->
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" required>
                            <div class="invalid-feedback">Please enter a valid Username!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" id="no_hp" required maxlength="14" oninput="validateNoHp(this)" onblur="checkMinLength()">
                            <div class="invalid-feedback">Please, enter your phone number!</div>
                            <!-- Display character counter -->
                            <small id="charCount">0/14</small>
                        </div>
                        <!-- Confirm Password and No. HP in one row -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password (*Minimal 8 karakter)</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                            <div class="invalid-feedback">Please enter your password!</div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                            <div class="invalid-feedback">Please confirm your password!</div>
                        </div>

                        <!-- Submit button -->
                        <div class="col-12">
                            <button class="btn w-100" style="color: white; background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }};" type="submit">Tambah</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
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
        function validateNoHp(input) {
            const maxLength = 14;
            let value = input.value;

            value = value.replace(/[^0-9]/g, '');

            if (value.length > maxLength) {
                value = value.slice(0, maxLength);
            }
            input.value = value;

            document.getElementById('charCount').innerText = `${value.length}/${maxLength}`;
        }

        function checkMinLength() {
            const input = document.getElementById('no_hp');
            const value = input.value;
    
            if (value.length < 10) {
                document.getElementById('invalid-feedback').style.display = 'block';
                input.classList.add('is-invalid');
            } else {
                document.getElementById('invalid-feedback').style.display = 'none';
                input.classList.remove('is-invalid');
            }
        }
    </script>
@endpush
