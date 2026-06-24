{{-- Google AdSense — loads only when a publisher ID is set, and only in production. --}}
@if(($adsenseId = settings('google_adsense_id')) && app()->environment('production'))
    <meta name="google-adsense-account" content="{{ $adsenseId }}">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseId }}" crossorigin="anonymous"></script>
@endif
