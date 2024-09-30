@extends('layouts.user_type.auth')

@section('content')
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/img/' . $profile->logo) }}" alt="Profile" class="rounded-circle" onerror="this.onerror=null;this.src='{{ asset('path/to/default-image.jpg') }}';">
                    <h2>{{ $admin->nama_admin }}</h2>
                    <h3>{{ $profile->nama_usaha }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-changePassword">Change Password</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        @include('profile.ringkasan')
                        @include('profile.edit')
                        @include('profile.settings')
                        @include('profile.changePassword')
                    </div><!-- End Bordered Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
