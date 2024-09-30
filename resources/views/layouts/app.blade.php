<!DOCTYPE html>

@if (\Request::is('rtl'))
  <html dir="rtl" lang="ar">
@else
  <html lang="en">
@endif

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  @if (env('IS_DEMO'))
      <x-demo-metas></x-demo-metas>
  @endif

  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    {{ $profile->nama_usaha }}
  </title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

  <!-- jQuery (remove duplicate) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('fontawesome/css/all.min.css') }}">

  <!-- Soft UI Dashboard CSS -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />

  <!-- SweetAlert2 -->
  <script src="{{ asset('dist/sweetalert2.all.min.js') }}"></script>

  <!-- DataTables CSS for Bootstrap -->
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




  <!-- Bootstrap CSS (latest version) -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->

  @yield('styles')
</head>

<body class="g-sidenav-show bg-gray-100 {{ (\Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '')) }}">
  
  @auth
    @yield('auth')
  @endauth
  
  @guest
    @yield('guest')
  @endguest

  <!-- @if(session()->has('success'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)"
         x-show="show"
         class="position-fixed bg-success rounded right-3 text-sm py-2 px-4">
      <p class="m-0">{{ session('success') }}</p>
    </div>
  @endif -->

  <!-- Core JS Files -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>

  @stack('rtl')
  @stack('dashboard')

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <!-- Control Center for Soft Dashboard -->
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>

  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

  <!-- DataTables Bootstrap JS -->
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

  <!-- Initialize DataTables -->
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>

  <!-- Include section scripts -->
  @yield('scripts')

</body>

</html>
