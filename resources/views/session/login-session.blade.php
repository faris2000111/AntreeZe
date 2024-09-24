@extends('layouts.user_type.guest')

@section('content')

<main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-primary">Selamat Datang</h3>
                  <p class="mb-0">Masukkan Username dan Password Anda untuk Masuk<br></p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" action="/session">
                    @csrf
                    <label>Username</label>
                    <div class="mb-3">
                      <input type="text" class="form-control" name="username" id="username" placeholder="Username" aria-label="Username" aria-describedby="username-addon">
                      @error('username')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                      @enderror
                    </div>

                    <label>Password</label>
                    <div class="mb-3 position-relative">
                      <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                      <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;"></span>
                      @error('password')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked="">
                      <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Masuk</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</main>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mendapatkan semua elemen dengan class .toggle-password
            const togglePasswordIcons = document.querySelectorAll('.toggle-password');
            
            togglePasswordIcons.forEach(icon => {
                // Mendapatkan input password yang terkait dengan icon
                const passwordField = document.querySelector(icon.getAttribute('toggle'));
                
                // Event listener ketika ikon diklik
                icon.addEventListener('click', function () {
                    // Toggle tipe input field password menjadi text dan sebaliknya
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Toggle kelas ikon mata
                    icon.classList.toggle('fa-eye-slash');
                });
            });
        });
    </script>
@endsection


