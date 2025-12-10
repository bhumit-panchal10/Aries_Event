<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">

                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('home') }}" class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="" height="80">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('assets/images/users/undraw_profile.webp') }}" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    @auth
                                        <span>Welcome, {{ auth()->user()->full_name }}</span>
                                    @else
                                        <span>Welcome, Guest</span>
                                    @endauth
                                </span>


                                @auth
                                    @php
                                        $role = App\Models\User::select('users.id', 'roles.name')
                                            ->join('roles', 'users.role_id', '=', 'roles.id')
                                            ->where('users.id', auth()->id())
                                            ->first();
                                    @endphp
                                    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                        {{ $role->name ?? 'User' }}
                                    </span>
                                @endauth

                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">
                            Welcome
                            @auth
                                {{ auth()->user()->full_name }}
                            @else
                                Guest
                            @endauth
                        </h6>
                        <a class="dropdown-item" href="{{ route('profile.detail') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
