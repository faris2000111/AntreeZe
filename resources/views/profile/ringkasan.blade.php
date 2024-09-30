<div class="tab-pane fade show active profile-overview" id="profile-overview">
    <h5 class="card-title">Tentang</h5>
    <p class="small fst-italic">...</p>

    <h5 class="card-title">Detail Profil</h5>
    <!-- Full Name -->
    <div class="row">
        <div class="col-lg-3 col-md-4 label">Nama Lengkap</div>
        <div class="col-lg-9 col-md-8">{{ $admin->nama_admin }}</div>
    </div>

    <!-- Company -->
    <div class="row">
        <div class="col-lg-3 col-md-4 label">Email</div>
        <div class="col-lg-9 col-md-8">{{ $admin->email }}</div>
    </div>

    <!-- Job -->
    <div class="row">
        <div class="col-lg-3 col-md-4 label">No. HP</div>
        <div class="col-lg-9 col-md-8">{{ $admin->no_hp }}</div>
    </div>

    <!-- Country -->
    <div class="row">
        <div class="col-lg-3 col-md-4 label">Username</div>
        <div class="col-lg-9 col-md-8">{{ $admin->username }}</div>
    </div>
</div>
