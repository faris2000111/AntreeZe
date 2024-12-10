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
                  <form role="form" method="POST" action="{{ url('/session') }}">
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
                      <div class="input-group">
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                          <!-- Ikon untuk toggle visibility password -->
                          <span class="input-group-text" onclick="togglePassword('password')" style="cursor: pointer;">
                                  <i class="fa fa-fw fa-eye field-icon toggle-password" id="passwordIcon"></i>
                          </span>

                      </div>
                          @error('password')
                              <p class="text-danger text-xs mt-2">{{ $message }}</p>
                          @enderror
                    </div>

                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked="">
                      <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn w-100 mt-4 mb-0" style="background-color: {{ $profile->warna }}; color: #FFF;">Masuk</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <small class="text-muted">Lupa Password? Reset Password Anda
                  <a href="{{ url('/login/forgot-password') }}" class="text font-weight-bold" style="color: {{ $profile->warna }};">disini!</a>
                </small>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url({{ asset('assets/img/curved-images/curved6.jpg') }})"></div>
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
    function togglePassword(passId) {
        var pass = document.getElementById(passId);
        var icon = document.getElementById('passwordIcon');

        if (pass.type === "password") {
            pass.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            pass.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

@endsection
