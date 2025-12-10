@php

    use Illuminate\Http\Request;
    $session = Session::get('customer_id');
    $count = \Cart::getContent()->count();
    $cartItems = \Cart::getContent();
    $wishlist_count = App\Models\Wishlist::where([
        'iStatus' => 1,
        'isDelete' => 0,
        'customerid' => $session,
    ])->count();

    $ip = request()->ip();
    $countryCode = getCountryCode($ip);

    if ($countryCode === 'IN') {
        $symbol = '₹';
    } else {
        $symbol = '$';
    }

@endphp

<!-- Header with Top Bar and Navigation -->
<header class="header-2025" id="mainHeader">
    <!-- Top Bar Section -->
    <section class="top-bar-section">
        <div class="top-bar">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="contact-info">
                    <a href="tel:+919876543210">
                        <i class="bi bi-telephone-fill"></i> +91 98765 43210
                    </a>
                    <span class="mx-3">|</span>
                    <a href="mailto:info@oroveda.com">
                        <i class="bi bi-envelope-fill"></i> info@oroveda.com
                    </a>
                </div>
                <!-- ===== NEW CURRENCY SWITCHER ===== -->

                <div class="social-links">
                    <a href="#" class="social-icon facebook" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-icon instagram" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-icon twitter" aria-label="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="social-icon youtube" aria-label="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <nav class="navbar-2025" id="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('front.index') }}" class="logo-container">
                <img src="https://z-cdn-media.chatglm.cn/files/ebf4f35e-4670-409e-9919-fce52af2755c_VArli%20art%20logo%20%282%29.png?auth_key=1791268795-d8f64ca02b6d4db397c7da0deff53a2d-0-ab1c221906f5e997a247bf82da051826"
                    alt="Oroveda Logo" class="logo-image">
            </a>

            <!-- Navigation Links -->
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="{{ route('front.index') }}" class="nav-link">Home</a></li>

                @php
                    $categories = \App\Models\Category::orderBy('strSequence', 'asc')->where('iStatus', 1)->get();
                    $currentCategorySlug = request()->route('categoryid') ?? request()->route('category_id');

                @endphp

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        Products <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="dropdown-menu-2025">

                        @foreach ($categories as $category)
                            <a href="{{ route('front.product_list', $category->slugname) }}" class="dropdown-item-2025">
                                <div class="dropdown-icon">
                                    <i class="bi bi-flower1"></i>
                                </div>
                                <div class="dropdown-content">
                                    <div class="dropdown-title">{{ $category->categoryname }}</div>
                                </div>
                                {{--  <span class="dropdown-badge">Bestseller</span>  --}}
                            </a>
                        @endforeach

                    </div>
                </li>

                <li class="nav-item"><a href="{{ route('front.about') }}" class="nav-link">About</a></li>
                <li class="nav-item"><a href="{{ route('front.blog') }}" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="{{ route('front.contact_us') }}" class="nav-link">Contact</a></li>

                @if (!$session)
                    <li class="nav-item"><a href="{{ route('front.login') }}" class="nav-cta">Login</a></li>
                @endif


                <!-- 🛒 Cart Menu -->
                <li class="nav-item cart-menu">
                    <a href="{{ route('cart.list') }}" class="nav-link cart-icon">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count" id="cartCount">{{ $count }}</span>
                    </a>
                    @if ($count > 0)
                        <div class="cart-dropdown" id="cartDropdown">
                            @foreach ($cartItems as $item)
                                <div class="cart-item">
                                    <img src="{{ asset('uploads/product') . '/' . $item->attributes->image }}"
                                        alt="{{ $item->name }}" class="cart-item-img">
                                    <div class="cart-item-details">
                                        <div class="cart-item-name">{{ $item->name }}</div>
                                        <div class="cart-item-price">
                                            {{ $symbol }}{{ $item->price . ' (' . $item->attribute_text . ')' }}
                                        </div>
                                        <div class="d-flex "><span class="cart-item-name"> Qty:
                                            </span> <span> {{ $item->quantity }}</span></div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="cart-footer d-flex justify-content-between">
                                <a href="{{ route('cart.list') }}" class="view-cart-btn">View Cart</a>
                                <div class="cart-total">Total: {{ $symbol }}{{ \Cart::getSubTotal() }}</div>

                            </div>
                        </div>
                    @endif
                </li>


                <li class="nav-item cart-menu">

                    @if (isset($session))
                        <a href="#" class="nav-link cart-icon">
                            <i class="bi bi-person"></i>
                        </a>
                    @endif

                    <div class="cart-dropdown" id="cartDropdown" style="width: 150px">
                        @foreach ($cartItems as $item)
                            <div class="cart-item">
                                <img src="{{ asset('uploads/product') . '/' . $item->attributes->image }}"
                                    alt="{{ $item->name }}" class="cart-item-img">
                                <div class="cart-item-details">
                                    <div class="cart-item-name">{{ $item->name }}</div>
                                    <div class="cart-item-price">
                                        {{ $symbol }}{{ $item->price . ' (' . $item->attribute_text . ')' }}
                                    </div>
                                    <div class="d-flex "><span class="cart-item-name"> Qty:
                                        </span> <span> {{ $item->quantity }}</span></div>
                                </div>
                            </div>
                        @endforeach

                        @if (isset($session))
                            <div class="cart-footer ">
                                <div class="cart-total"
                                    style="text-align:start;  border-bottom: 1px solid #ccc; color:#2a7d3e;font-weight: 100;padding-bottom: 5px">
                                    <a href="{{ route('front.profile') }}">
                                        <i class="bi bi-person-circle"></i> My
                                        Profile
                                    </a>
                                </div>
                                <div class="cart-total"
                                    style="text-align:start; color:#2a7d3e;font-weight: 100;padding-bottom: 5px">
                                    <a href="{{ route('front.logout') }}">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </li>
            </ul>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileToggle">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>


    <!-- Mobile Menu Overlay -->
    <!-- <div class="menu-overlay" id="menuOverlay"></div> -->
</header>

<!-- Header Placeholder (appears when header is fixed) -->
<div class="header-placeholder" id="headerPlaceholder"></div>
