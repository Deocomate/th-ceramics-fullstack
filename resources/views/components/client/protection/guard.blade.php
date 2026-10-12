@if ($protectionConfig)
    @if ($protectionConfig['deterrence'])
    <style>
        img, video, canvas, picture { -webkit-touch-callout: none; }
    </style>
    @endif
    <script>window.__contentProtection = @json($protectionConfig);</script>
    @if ($protectionConfig['devtools'])
    <script defer src="{{ asset('assets/js/vendor/disable-devtool.min.js') }}"></script>
    @endif
    <script defer src="{{ \App\Support\AssetPath::built('content-protection.min.js') }}"></script>
@endif
