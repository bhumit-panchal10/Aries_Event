@extends('layouts.front')

@section('title', 'My Account')

@section('content')

    @include('common.frontmodalalert')

    <div class="container my-5">
        <div class="row">
            <div class="col-md-4 mx-auto">
                @include('frontview.after_login.tabview')
            </div>
        </div>

        <div class="tab-content-container">

            <div id="my-profile-content" class="tab-pane-content">
                <div class="orders-content-wrapper">
                    <h1 class="page-title mb-4">My Profile</h1>
                    <div class="row">
                        <div class="col-lg-7 mb-4">
                            <div class="card profile-card">
                                <div class="card-header">
                                    <h5 class="card-title-profile mb-0">
                                        <i class="bi bi-person-circle me-2"></i>Profile Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="profileInfoForm">
                                        <div class="mb-3">
                                            <label for="profileName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="profileName"
                                                value="{{ $customer->customername }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="profileEmail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="profileEmail"
                                                value="{{ $customer->customeremail }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="profilePhone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="profilePhone"
                                                value="{{ $customer->customermobile }}" disabled>
                                        </div>
                                        <button type="submit" class="btn btn-save-changes w-100 mt-2 d-none">Save
                                            Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 mb-4">
                            <div class="card profile-card">
                                <div class="card-header">
                                    <h5 class="card-title-profile mb-0">
                                        <i class="bi bi-geo-alt-fill me-2"></i>Shipping Address
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="address-text">
                                        <strong>{{ $customer->customername }}</strong><br>
                                        @if ($customer->billStreetAddress1)
                                            {{ $customer->billStreetAddress1 }}<br>
                                        @endif
                                        @if ($customer->billStreetAddress2)
                                            {{ $customer->billStreetAddress2 }}<br>
                                        @endif
                                        {{ $customer->city }}, {{ $customer->state }} - {{ $customer->pincode }}<br>
                                        {{ $Country->countryName }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
