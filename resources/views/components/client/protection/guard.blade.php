@if ($protectionConfig)
    @if ($protectionConfig['deterrence'])
    <style>
        img, video, canvas, picture { -webkit-touch-callout: none; }
    </style>
    @endif
    {{-- The guard is a script, so a browser with JavaScript turned off gets no content to copy. --}}
    <noscript>
        <style>
            body > * { display: none !important; }
            body::before {
                content: "Vui lòng bật JavaScript trong trình duyệt để truy cập website Thanh Hải Ceramics.";
                display: block;
                padding: 64px 24px;
                text-align: center;
                font: 600 18px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
                color: #2E2F2A;
            }
        </style>
    </noscript>
    <script>window.__contentProtection = @json($protectionConfig);</script>
    @if ($protectionConfig['devtools'])
    <script defer src="{{ asset('assets/js/vendor/disable-devtool.min.js') }}"></script>
    @endif
    <script defer src="{{ \App\Support\AssetPath::built('content-protection.min.js') }}?v={{ filemtime(public_path('assets/dist/content-protection.min.js')) }}"></script>
@endif
