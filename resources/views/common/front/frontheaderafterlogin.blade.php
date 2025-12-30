<!-- Header -->
<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            <li><a href="tel:919123456789">
                                    <i class="ti-headphone-alt"></i> +91 90165 81643</a></li>
                            <li><a href="mailto:info@thewordrobefashion.com">
                                    <i class="ti-email"></i> info@thewardrobefashion.in</a></li>
                        </ul>
                    </div>
                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-7 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            <!-- <li><i class="ti-location-pin"></i> Store location</li> -->
                            {{--  <li><i class="ti-alarm-clock"></i> <a href="productlisting.php">Daily deal</a></li>  --}}
                            <li><i class="ti-user"></i> <a href="{{ route('myaccount') }}">Welcome
                                    {{ Session::get('customername') }}</a></li>
                            <li><i class="ti-power-off"></i><a href="{{ route('Frontlogout') }}">LogOut</a></li>
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="{{ route('FrontIndex') }}">
                            <img src="{{ asset('assets/front/images/logo-2.png') }}" alt="logo">
                        </a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <form class="search-form" action="productlisting.php">
                                <input type="text" placeholder="Search here..." name="search">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
                    <div class="mobile-nav"></div>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <form action="{{ route('HeaderSearch') }}" method="post">
                            @csrf
                            <div class="search-bar">
                                <?php
                                $Category = App\Models\Category::where('subcategoryid', 0)
                                    ->where(['iStatus' => 1 , 'isDelete' => 0])
                                    ->orderBy('categoryname', 'asc')->get();
                                ?>
                                <select name="categorysearch">
                                    <option value="0" selected="selected">All Category</option>
                                    @foreach ($Category as $category)
                                        <option value="{{ $category->categoryId }}">{{ $category->categoryname }}
                                        </option>
                                    @endforeach
                                </select>
                                <input name="headersearch" id="headersearch" placeholder="Search Products Here....."
                                    type="search">
                                <button type="submit" class="btnn"><i class="ti-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12 right-bar">
                    @php
                        $count = \Cart::getContent()->count();
                        $cartItems = \Cart::getContent();
                    @endphp
                    <!-- Search Form -->
                    
                    <div class="sinlge-bar shopping">
                        <a href="#" class="single-icon">
                            <i class="ti-bag"></i>
                            <span class="total-count">{{ $count }}</span>
                        </a>
                        <!-- Shopping Item -->
                        <div class="shopping-item">
                            <div class="dropdown-cart-header">
                                <span>{{ $count }} Items</span>
                                <a href="{{ route('cart.list') }}">View Cart</a>
                            </div>
                            <ul class="shopping-list">

                                @foreach ($cartItems as $items)
                                    <li>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $items->id }}" name="id">
                                            <button type="submit">
                                                <a href="#" class="remove" title="Remove this item">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </button>
                                            <a class="cart-img" href="#">
                                                <img src="{{ asset('Product') . '/' . $items->attributes->image }}"
                                                    alt="#">
                                            </a>
                                            <h4><a href="#">{{ $items->name }}</a></h4>
                                            <p class="quantity">{{ $items->quantity }} x
                                                <span class="amount">&#x20B9; {{ $items->price }}</span>
                                            </p>
                                    </li>
                                @endforeach

                            </ul>
                            <div class="bottom">
                                <div class="total">
                                    <span>Total</span>
                                    <span class="total-amount">&#x20B9; {{ Cart::getTotal() }}</span>
                                </div>
                                <a href="{{ route('checkout') }}" class="btn animate">Checkout</a>
                            </div>
                        </div>
                        <!--/ End Shopping Item -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav">
                                            <li class="@if (request()->routeIs('FrontIndex')) {{ 'active' }} @endif">
                                                <a href="{{ route('FrontIndex') }}">Home</a>
                                            </li>
                                            <li class="@if (request()->routeIs('FrontProduct')) {{ 'active' }} @endif">
                                                <a href="{{ route('FrontProduct') }}">
                                                    NEW ARRIVALS
                                                </a>
                                            </li>
                                            <li>
                                                <?php
                                                $Category = App\Models\Category::where('subcategoryid', 0)->orderBy('categoryname', 'asc')->get();
                                                ?>
                                                <a href="#">CATEGORIES<i class="ti-angle-down"></i></a>
                                                <ul class="dropdown">
                                                    @foreach ($Category as $category)
                                                        <li> <a
                                                                href="{{ route('FrontCategory', $category->slugname) }}">{{ $category->categoryname }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            <li class="@if (request()->routeIs('FrontContactUs')) {{ 'active' }} @endif">
                                                <a href="{{ route('FrontContactUs') }}">Contact Us</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>
<!--/ End Header -->
