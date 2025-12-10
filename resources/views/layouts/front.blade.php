<!DOCTYPE html>
<html lang="en">

@include('common.front.fronthead')

<body id="page-top">

    @include('common.front.frontheader')

    @yield('content')

    @include('common.front.frontfooter')

    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    @include('common.front.frontfooterjs')

    @yield('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>



</html>
