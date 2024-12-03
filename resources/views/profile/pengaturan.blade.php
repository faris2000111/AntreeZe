@extends('layouts.user_type.auth')

@section('content')
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                    <!-- Display profile logo -->
                    @if($profile->logo)
                        <img src="{{ $profile->logo }}" alt="Logo" height="100" width="100" class="rounded-circle">
                    @else
                        <img src="{{ asset('assets/img/default-logo.jpg') }}" alt="Logo" class="rounded-circle">
                    @endif

                    <h2>{{ $profile->nama_usaha ?? 'Business Name' }}</h2>

                    <!-- Display banner if available -->
                    @if($profile->banner)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $profile->banner) }}" alt="Banner" class="img-fluid">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Success message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Edit Profile Form -->
                    <form method="POST" action="{{ route('pengaturan.update', $profile->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- First row (Nama Usaha, Warna Usaha, Jam Buka) -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="namaUsaha" class="col-form-label">Nama Usaha</label>
                                <input name="nama_usaha" type="text" class="form-control" id="namaUsaha" value="{{ $profile->nama_usaha }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="warna" class="col-form-label">Warna Usaha</label>
                                <select name="warna" id="warna" class="form-control" style="background-color: {{ $profile->warna }}; color: #FFF;">
                                    <option value="#2152FF" style="background-color: #2152FF; color: white;" {{ $profile->warna == '#2152FF' ? 'selected' : '' }}>Biru (#2152FF)</option>
                                    <option value="#FF5722" style="background-color: #FF5722; color: white;" {{ $profile->warna == '#FF5722' ? 'selected' : '' }}>Oranye (#FF5722)</option>
                                    <option value="#4CAF50" style="background-color: #4CAF50; color: white;" {{ $profile->warna == '#4CAF50' ? 'selected' : '' }}>Hijau (#4CAF50)</option>
                                    <option value="#FFC107" style="background-color: #FFC107; color: black;" {{ $profile->warna == '#FFC107' ? 'selected' : '' }}>Kuning (#FFC107)</option>
                                    <option value="#9C27B0" style="background-color: #9C27B0; color: white;" {{ $profile->warna == '#9C27B0' ? 'selected' : '' }}>Ungu (#9C27B0)</option>
                                    <option value="#00BCD4" style="background-color: #00BCD4; color: white;" {{ $profile->warna == '#00BCD4' ? 'selected' : '' }}>Cyan (#00BCD4)</option>
                                    <option value="#F44336" style="background-color: #F44336; color: white;" {{ $profile->warna == '#F44336' ? 'selected' : '' }}>Merah (#F44336)</option>
                                    <option value="#E91E63" style="background-color: #E91E63; color: white;" {{ $profile->warna == '#E91E63' ? 'selected' : '' }}>Pink (#E91E63)</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="jamBuka" class="col-form-label">Jam Buka</label>
                                <input name="jam_buka" type="time" class="form-control" id="jamBuka" value="{{ $profile->jam_buka }}">
                            </div>
                        </div>

                        <!-- Second row (Jam Tutup, Logo Upload, Banner 1 Upload) -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="jamTutup" class="col-form-label">Jam Tutup</label>
                                <input name="jam_tutup" type="time" class="form-control" id="jamTutup" value="{{ $profile->jam_tutup }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="profileImage" class="col-form-label">Logo Usaha (*jpg/jpeg/png/svg dan max 1MB)</label>
                                @if($profile->logo)
                                    <img src="{{ $profile->logo }}" alt="Logo" height="100" width="100" class="rounded mb-2">
                                @endif
                                <input type="file" name="logo" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="banner1" class="col-form-label">Banner 1 (*jpg/jpeg/png/svg dan max 1MB)</label>
                                @if($profile->banner1)
                                    <img src="{{ $profile->banner1 }}" alt="Banner1" height="100" width="150" class="rounded mb-2">
                                @endif
                                <input type="file" name="banner1" class="form-control">
                            </div>
                        </div>

                        <!-- Third row (Banner 2 Upload, Banner 3 Upload) -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="banner2" class="col-form-label">Banner 2 (*jpg/jpeg/png/svg dan max 1MB)</label>
                                @if($profile->banner2)
                                    <img src="{{ $profile->banner2 }}" alt="Banner2" height="100" width="150" class="rounded mb-2">
                                @endif
                                <input type="file" name="banner2" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="banner3" class="col-form-label">Banner 3 (*jpg/jpeg/png/svg dan max 1MB)</label>
                                @if($profile->banner3)
                                    <img src="{{ $profile->banner3 }}" alt="Banner3" height="100" width="150" class="rounded mb-2">
                                @endif
                                <input type="file" name="banner3" class="form-control">
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="text-center">
                            <button type="submit" class="btn" style="background-color: {{ $profile->warna }}; color: #FFF;">Simpan</button>
                        </div>
                    </form><!-- End Profile Edit Form -->

                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert notification for success -->
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
