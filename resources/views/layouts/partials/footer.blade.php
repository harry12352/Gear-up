@if (bodyClass() != "login" && bodyClass() != "register")
    <footer class="border-top px-1 py-4">
        <p class="m-0 text-center text-muted small">GearWRX &copy; 2020 All Rights Reserved</p>
    </footer>
@endif

<!-- Scripts -->
<script>
    [
        '{{ asset("js/scripts.min.js") }}',
    ].forEach(function (src) {
        let script = document.createElement('script');
        script.src = src;
        script.async = false;
        document.head.appendChild(script);
    });
</script>
{{--<script src="{{ asset('js/app.js') }}" async defer></script>--}}
