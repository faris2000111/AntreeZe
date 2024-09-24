@extends('layouts.app')

@section('guest')
    @if(\Request::is('login/forgot-password')) 
        @yield('content') 
        @yield('scripts')
    @else
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">

            </div>
        </div>
        @yield('content')   
        @yield('scripts')     
        @include('layouts.footers.guest.footer')
    @endif
@endsection