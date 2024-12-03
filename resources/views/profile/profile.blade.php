@extends('layouts.user_type.auth')

@section('content')
<section class="section profile">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4">
            <div class="card mb-4" style="margin: 20px;">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <!-- Avatar -->
                    <img src="{{ $admin->avatar }}" alt="Avatar {{ $admin->nama_admin }}" width="150" height="150" class="rounded-circle shadow-sm mb-3">
                    <h4>{{ auth()->user()->nama_admin }}</h4>
                    <h5 class="text-muted">{{ $profile->nama_usaha }}</h5>
                </div>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="col-xl-8">
            <div class="card mb-4" style="margin: 20px;">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Ringkasan</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Ubah Profil</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">
                        <!-- Profile Overview -->
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Tentang</h5>
                            <p class="small fst-italic">Detail dan informasi profil admin.</p>

                            <h5 class="card-title">Detail Profil</h5>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label">Nama Lengkap</div>
                                <div class="col-lg-9 col-md-8">{{ auth()->user()->nama_admin }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label">Username</div>
                                <div class="col-lg-9 col-md-8">{{ auth()->user()->username }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8">{{ auth()->user()->email }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label">Nomor HP</div>
                                <div class="col-lg-9 col-md-8">{{ auth()->user()->no_hp }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label">Peran</div>
                                <div class="col-lg-9 col-md-8">{{ auth()->user()->role }}</div>
                            </div>
                        </div>

                        <!-- Profile Edit Form -->
                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                            <form method="POST" action="{{ route('profile.update', auth()->user()->id_admin) }}" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')
                                
                                <!-- Avatar Edit -->
                                <div class="row mb-3">
                                    <label for="avatar" class="col-md-4 col-lg-3 col-form-label">Avatar</label>
                                    <div class="col-md-8 col-lg-9">
                                        @if($admin->avatar)
                                            <img src="{{ $admin->avatar }}" alt="Avatar" height="100" width="100" class="rounded mb-2">
                                        @endif
                                        <input name="avatar" type="file" class="form-control" id="avatar" accept="image/*">
                                    </div>
                                </div>

                                <!-- Nama Lengkap -->
                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nama_admin" type="text" class="form-control" id="fullName" value="{{ auth()->user()->nama_admin }}">
                                    </div>
                                </div>

                                <!-- Username -->
                                <div class="row mb-3">
                                    <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="username" type="text" class="form-control" id="username" value="{{ auth()->user()->username }}" readonly>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="email" value="{{ auth()->user()->email }}">
                                    </div>
                                </div>

                                <!-- No HP -->
                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-lg-3 col-form-label">No. HP</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="no_hp" type="text" class="form-control" id="phone" value="{{ auth()->user()->no_hp }}">
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn" style="background-color: {{ $profile->warna }}; color: #FFF;">Simpan</button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Form -->
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <form method="POST" action="{{ route('profile.update', auth()->user()->id_admin) }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password Saat Ini</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="current_password" type="password" class="form-control" id="currentPassword" required>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password" type="password" class="form-control" id="newPassword" required>
                                    </div>
                                </div>

                                <!-- Confirm New Password -->
                                <div class="row mb-3">
                                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password_confirmation" type="password" class="form-control" id="renewPassword" required>
                                    </div>
                                </div>

                                <!-- Save Password Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn" style="background-color: {{ $profile->warna }}; color: #FFF;">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- End Bordered Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success and Error Notifications -->
@if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection
