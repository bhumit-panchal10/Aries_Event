<!DOCTYPE html>
<html lang="en">

@include('auth.includes.head')

<body>
    <div class="auth-page-wrapper" style="overflow: hidden;">
        @yield('content')
    </div>
    @include('auth.includes.scripts')
</body>

</html>
