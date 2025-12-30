<!doctype html>
<html lang="en" data-layout="horizontal" data-layout-style="default" data-layout-position="fixed" data-topbar="light"
    data-sidebar="dark" data-sidebar-size="sm-hover" data-layout-width="fluid">

{{-- Include Head --}}
@include('common.admin.head')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Topbar -->
        @include('common.admin.header')
        <!-- End of Topbar -->

        <!-- Sidebar -->
        @include('common.admin.sidebar')
        <!-- End of Sidebar -->

        @yield('content')

        @include('common.admin.footer')

    </div>

    @include('common.admin.footerjs')

    @yield('scripts')

</body>

</html>
