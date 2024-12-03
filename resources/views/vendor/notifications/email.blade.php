<x-mail::message>
{{-- Logo --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{$profile->logo}}" class="navbar-brand-img h-100" alt="{{$profile->nama_usaha}}">
</div>

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Oops!')
@else
# @lang('Halo!')
@endif
@endif

{{-- Intro Lines --}}
<p style="font-size: 16px; color: #333; line-height: 1.5;">
Anda menerima email ini karena kami menerima permintaan untuk mereset kata sandi akun Anda.
</p>

{{-- Action Button --}}
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-transform: uppercase; border-radius: 5px;">
Reset Kata Sandi
</x-mail::button>

{{-- Outro Lines --}}
<p style="font-size: 16px; color: #333; line-height: 1.5;">Tautan reset kata sandi ini akan kedaluwarsa dalam 60 menit.</p>
<p style="font-size: 16px; color: #333; line-height: 1.5;">Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
<p style="font-size: 16px; color: #333;">
    @lang('Salam hormat'),<br>
    {{ $profile->nama_usaha }}
</p>
@endif

{{-- Social Media Icons --}}
<div style="text-align: center; margin-top: 30px;">
    <a href="https://facebook.com/yourpage" target="_blank">
        <img src="https://antreeze.kingperseus.online/assets/img/facebook.png" alt="Facebook" style="width: 30px; margin-right: 10px;">
    </a>
    <a href="https://tiktok.com/yourpage" target="_blank">
        <img src="https://antreeze.kingperseus.online/assets/img/tiktok.png" alt="Tiktok" style="width: 30px; margin-right: 10px;">
    </a>
    <a href="https://instagram.com/yourpage" target="_blank">
        <img src="https://antreeze.kingperseus.online/assets/img/instagram.png" alt="Instagram" style="width: 30px;">
    </a>
</div>

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
    <p style="font-size: 14px; color: #888;">
        @lang(
            "Jika Anda kesulitan mengklik tombol \":actionText\", salin dan tempel URL di bawah ini ke browser Anda:",
            ['actionText' => $actionText]
        )
        <br>
        <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
    </p>
</x-slot:subcopy>
@endisset
</x-mail::message>
