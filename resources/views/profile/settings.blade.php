<div class="tab-pane fade pt-3" id="profile-settings">
    <form method="POST" action="{{ route('profile.update', $admin->id_admin) }}">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <label for="emailNotifications" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
            <div class="col-md-8 col-lg-9">
                <div class="form-check">

                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
