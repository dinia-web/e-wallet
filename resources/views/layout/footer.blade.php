<script src="{{ asset('js/modal.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.appConfig = {
        success: @json(session('success')),
        error: @json(session('error')),
        errors: @json($errors->all())
    };
</script>

</body>
</html>