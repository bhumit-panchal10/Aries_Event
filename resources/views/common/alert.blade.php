{{-- Message --}}
@if (Session::has('success'))
    <!-- Success Alert -->
    <div id="successAlert" class="alert alert-primary alert-dismissible alert-solid alert-label-icon fade show"
        role="alert">
        <i class="ri-user-smile-line label-icon"></i>
        <strong>Success !</strong> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (Session::has('error'))
    <!-- Danger Alert -->
    <div id="errorAlert" class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show"
        role="alert">
        <i class="ri-error-warning-line label-icon"></i>
        <strong>Error !</strong> {{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        document.getElementById('successAlert').remove();
        document.getElementById('errorAlert').remove();
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
