<div class="tab-pane fade" id="profile-edit">
    <form method="POST" action="{{ route('profile.edit', $admin->id_admin) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            <h5 class="card-title">Edit Profil</h5>

            <!-- Nama Lengkap -->
            <div class="row mb-3">
                <label for="nama_admin" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                <div class="col-md-8 col-lg-9">
                    <input type="text" class="form-control" id="nama_admin" name="nama_admin" value="{{ $admin->nama_admin }}" required>
                </div>
            </div>

            <!-- Email -->
            <div class="row mb-3">
                <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                <div class="col-md-8 col-lg-9">
                    <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
                </div>
            </div>

            <!-- No. HP -->
            <div class="row mb-3">
                <label for="no_hp" class="col-md-4 col-lg-3 col-form-label">No. HP</label>
                <div class="col-md-8 col-lg-9">
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $admin->no_hp }}" required>
                </div>
            </div>

            <!-- Username -->
            <div class="row mb-3">
                <label for="username" class="col-md-4 col-lg-3 col-form-label">Username</label>
                <div class="col-md-8 col-lg-9">
                    <input type="text" class="form-control" id="username" name="username" value="{{ $admin->username }}" required>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
