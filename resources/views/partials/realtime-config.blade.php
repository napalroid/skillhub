<script>
    window.SkillHubRealtime = {
        key: @json(config('broadcasting.connections.reverb.key')),
        host: '127.0.0.1',
        port: 8080,
        scheme: 'http',
    };
</script>
<script src="{{ asset('realtime-config.js') }}?v={{ filemtime(public_path('realtime-config.js')) }}"></script>
