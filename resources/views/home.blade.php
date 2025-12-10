@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            {{--  <h4 class="fs-16 mb-1">Admin Login</h4>  --}}
                                        </div>

                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->



                            <div class="row">

                                @if (Auth::user()->role_id == 1)
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card card-animate" style="background: #570f29;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-bold  text-truncate mb-0">
                                                            Pending Inquiry</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-bold ff-secondary  mb-4"><span
                                                                class="counter-value"
                                                                data-target="{{ $pending_inquiry }}">0</span>
                                                        </h4>
                                                        <a href="{{ route('inquiry.pending_inquirylist') }}"
                                                            class="text-decoration-underline -50">View
                                                            Pending Inquiry</a>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-dashboard rounded fs-3">
                                                            <i class="fa-solid fa-box-open"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card card-animate" style="background: #570f29;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-bold  text-truncate mb-0">
                                                            Schedule Inquiry</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-bold ff-secondary  mb-4"><span
                                                                class="counter-value"
                                                                data-target="{{ $schedule_inquiry }}">0</span>
                                                        </h4>
                                                        <a href="{{ route('inquiry.schedule_reschedule_inquirylist') }}"
                                                            class="text-decoration-underline -50">View
                                                            Schedule Inquiry</a>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-dashboard rounded fs-3">
                                                            <i class="fa-solid fa-box-open"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card card-animate" style="background: #570f29;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-bold  text-truncate mb-0">
                                                            Cancel Inquiry</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-bold ff-secondary  mb-4"><span
                                                                class="counter-value"
                                                                data-target="{{ $cancel_inquiry }}">0</span>
                                                        </h4>
                                                        <a href="{{ route('inquiry.cancel_list') }}"
                                                            class="text-decoration-underline -50">View
                                                            Cancel Inquiry</a>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-dashboard rounded fs-3">
                                                            <i class="fa-solid fa-box-open"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card card-animate" style="background: #570f29;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-bold  text-truncate mb-0">
                                                            Deal Done Inquiry</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-bold ff-secondary  mb-4"><span
                                                                class="counter-value"
                                                                data-target="{{ $dealdone_inquiry }}">0</span>
                                                        </h4>
                                                        <a href="{{ route('inquiry.dealdone_list') }}"
                                                            class="text-decoration-underline -50">View
                                                            Deal Done Inquiry</a>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-dashboard rounded fs-3">
                                                            <i class="fa-solid fa-box-open"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card card-animate" style="background: #570f29;">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-bold  text-truncate mb-0">
                                                            Pending Order Delivery</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-bold ff-secondary  mb-4"><span
                                                                class="counter-value"
                                                                data-target="{{ $PendingOrderDelivery }}">0</span>
                                                        </h4>
                                                        <a href="{{ route('order.userpending') }}"
                                                            class="text-decoration-underline -50">
                                                            View Pending Order Delivery</a>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-dashboard rounded fs-3">
                                                            <i class="fa-solid fa-circle-question"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ env('APP_NAME') }}
                    </div>

                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->


@endsection
