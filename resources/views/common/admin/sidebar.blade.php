<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">

                @auth
                    @if (Auth::user()->role_id == 1)
                        <li class="menu-title"><span data-key="t-menu"></span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                                href="{{ route('home') }}">
                                <i class="mdi mdi-view-dashboard-outline"></i>
                                <span data-key="t-dashboards">Dashboards</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                                aria-expanded="true" aria-controls="sidebarMore">
                                <i class="ri-database-2-line"></i> Master Entry </a>
                            <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('blog.index')) {{ 'active' }} @endif"
                                            href="{{ route('blog.index') }}">
                                            <i class="fa-solid fa-blog"></i>
                                            <span data-key="t-dashboards">Blog</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('testimonial.index')) {{ 'active' }} @endif"
                                            href="{{ route('testimonial.index') }}">
                                            <i class="fa-solid fa-comments"></i>
                                            <span data-key="t-dashboards">Testimonial</span>
                                        </a>
                                    </li>

                                    {{-- <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('testimonial.index')) {{ 'active' }} @endif"
                                            href="{{ route('metaData.index') }}">
                                            <i class="fa-solid fa-clipboard-list"></i>
                                            <span data-key="t-dashboards">Meta Data</span>
                                        </a>
                                    </li> --}}

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('testimonial.index')) {{ 'active' }} @endif"
                                            href="{{ route('faq.index') }}">
                                            <i class="fa-regular fa-circle-question"></i>
                                            <span data-key="t-dashboards">Faq</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('testimonial.index')) {{ 'active' }} @endif"
                                            href="{{ route('year.index') }}">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span data-key="t-dashboards">Year</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="" data-bs-toggle="collapse" role="button" aria-expanded="true"
                                aria-controls="sidebarMore">
                                <i class="fa-solid fa-circle-question"></i> Inquiry </a>
                            <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('inquiry.pending_inquirylist')) {{ 'active' }} @endif"
                                            href="{{ route('inquiry.pending_inquirylist') }}">
                                            <i class="fa-regular fa-clock"></i>
                                            <span data-key="t-dashboards">Pending Inquiry</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('inquiry.schedule_reschedule_inquirylist')) {{ 'active' }} @endif"
                                            href="{{ route('inquiry.schedule_reschedule_inquirylist') }}">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span data-key="t-dashboards">Schedule Inquiry</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('inquiry.cancel_list')) {{ 'active' }} @endif"
                                            href="{{ route('inquiry.cancel_list') }}">
                                            <i class="fa-solid fa-xmark"></i> <span data-key="t-dashboards">Cancel
                                                Inquiry</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('inquiry.cancel_list')) {{ 'active' }} @endif"
                                            href="{{ route('inquiry.dealdone_list') }}">
                                            <i class="fa-solid fa-circle-check"></i> <span data-key="t-dashboards">Deal Done
                                                Inquiry</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>



                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link menu-link @if (request()->routeIs('metaData.index')) {{ 'active' }} @endif"-->
                        <!--        href="{{ route('metaData.index') }}">-->
                        <!--        <i class="fa-solid fa-magnifying-glass"></i>-->
                        <!--        <span data-key="t-dashboards">Seo</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                                aria-expanded="true" aria-controls="sidebarMore">
                                <i class="fa-solid fa-gear"></i> Setting </a>
                            <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('setting.index')) {{ 'active' }} @endif"
                                            href="{{ route('setting.index') }}">
                                            <i class="fa-solid fa-star"></i>
                                            <span data-key="t-dashboards">Web Setting</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('otherpages.index')) {{ 'active' }} @endif"
                                            href="{{ route('otherpages.index') }}">
                                            <i class="fa-solid fa-file-lines"></i>
                                            <span data-key="t-dashboards">Other Pages </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}

                        {{--  <li class="nav-item">
                            <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                                aria-expanded="true" aria-controls="sidebarMore">
                                <i class="ri-briefcase-2-line"></i> Reports </a>
                            <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('setting.index')) {{ 'active' }} @endif"
                                            href="{{ route('report.paymentReport') }}">
                                            <i class="fa-solid fa-indian-rupee-sign"></i>
                                            <span data-key="t-dashboards">Payment Report</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('otherpages.index')) {{ 'active' }} @endif"
                                            href="{{ route('report.orderTracking') }}">
                                            <i class="fas fa-shipping-fast"></i>
                                            <span data-key="t-dashboards">Order Tracking</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link menu-link @if (request()->routeIs('report.searchCustomer')) {{ 'active' }} @endif"
                                            href="{{ route('report.searchCustomer') }}">
                                            <i class="fa-solid fa-user"></i>
                                            <span data-key="t-dashboards">Customer Search</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>  --}}

                        {{--  <li class="nav-item">
                        <a target="_blank" class="nav-link menu-link"
                            href="https://marketingplatform.google.com/about/analytics/">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span data-key="t-dashboards">Google Analytics</span>
                        </a>
                    </li>  --}}
                    @else
                        <li class="nav-item">
                            <a class="nav-link menu-link @if (request()->routeIs('order.userpending')) {{ 'active' }} @endif"
                                href="{{ route('order.userpending') }}">
                                <i class="ri-briefcase-2-line"></i>
                                <span data-key="t-dashboards">Order</span>
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
