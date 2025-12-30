@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        {{-- Alert Messages --}}
        @include('common.alert')

        <div class="page-content">
            <div class="container-fluid mt-4">
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src="{{ asset('assets/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
                    </div>
                </div>
                <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                    <div class="row g-4">
                        <div class="col-auto">
                            <div class="avatar-lg">
                                <img src="{{ asset('assets/images/users/undraw_profile.webp') }}" alt="user-img"
                                    class="img-thumbnail rounded-circle" />
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ auth()->user()->full_name }}</h3>
                                <?php
                                $session = auth()->user()->id;
                                $role = App\Models\User::select('users.id', 'roles.name')->where('users.id', $session)->join('roles', 'users.role_id', '=', 'roles.id')->first();
                                ?>
                                <p class="text-white-75">
                                    {{ $role->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="d-flex">
                                <!-- Nav tabs -->
                                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab"
                                            role="tab">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Overview</span>
                                        </a>
                                    </li>

                                </ul>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('profile.EditProfile') }}" class="btn btn-primary"><i
                                            class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                                </div>
                            </div>
                            <!-- Tab panes -->
                            <div class="tab-content pt-4 text-muted">
                                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title mb-3">User Information</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="ps-0" scope="row">Full Name :</th>
                                                                    <td class="text-muted">
                                                                        {{ old('first_name') ? old('first_name') : auth()->user()->full_name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="ps-0" scope="row">Mobile :</th>
                                                                    <td class="text-muted">
                                                                        {{ old('mobile_number') ? old('mobile_number') : auth()->user()->mobile_number }}
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                                    <td class="text-muted">
                                                                        {{ old('email') ? old('email') : auth()->user()->email }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="ps-0" scope="row">User Type :</th>
                                                                    <td class="text-muted">
                                                                        {{ $role->name }}
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © PY Engineering.
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
