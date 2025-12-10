@extends('layouts.front')

@section('title', 'Product Listing')

@section('content')

    <style>
        .filter-btn {
            border: 1px solid #2a7d3e;
            color: #2a7d3e;
            background-color: transparent;
            border-radius: 20px;
            padding: 6px 18px;
            margin: 4px;
            font-weight: 600;
            transition: 0.3s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #2a7d3e, #8bc34a);
            color: #fff;
            border-color: transparent;
        }
    </style>

    @include('common.frontmodalalert')

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>

        <div class="header-content">
            <h1>Products</h1>

            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Products</li>
                </ul>
            </nav>
        </div>
    </section>

    <section id="products" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Ghee Collection</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Explore our complete range of premium gir cow ghee products.
                </p>
            </div>


            <div class="filter-section" data-aos="fade-up">
                <h3 style="margin-bottom: 20px;">Filter Products</h3>

                <a href="{{ route('front.product_list', $categoryid) }}"
                    class="filter-btn {{ request('filter') == '' ? 'active' : '' }}">All</a>

                <a href="{{ route('front.product_list', [$categoryid, 'filter' => 'bestsellers']) }}"
                    class="filter-btn {{ request('filter') == 'bestsellers' ? 'active' : '' }}">Bestsellers</a>

                <a href="{{ route('front.product_list', [$categoryid, 'filter' => 'newarrivals']) }}"
                    class="filter-btn {{ request('filter') == 'newarrivals' ? 'active' : '' }}">New Arrivals</a>

                <a href="{{ route('front.product_list', [$categoryid, 'filter' => 'giftboxes']) }}"
                    class="filter-btn {{ request('filter') == 'giftboxes' ? 'active' : '' }}">Gift Boxes</a>

                <a href="{{ route('front.product_list', [$categoryid, 'filter' => 'combopacks']) }}"
                    class="filter-btn {{ request('filter') == 'combopacks' ? 'active' : '' }}">Combo Packs</a>
            </div>


            <div class="row g-4 product-category">

                @foreach ($products as $product)
                    @php
                        if ($countryCode === 'IN') {
                            $price = $product->rate; // INR price
                            $cut_price = $product->cut_price; // INR price
                            $symbol = '₹';
                        } else {
                            $price = $product->usd_rate; // USD price
                            $cut_price = $product->usd_cut_price; // INR price
                            $symbol = '$';
                        }
                    @endphp
                    <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                        <div class="card h-100 shadow-sm border-0 product-card">
                            <a href="{{ route('front.product_detail', [$product->category_slug, $product->slugname]) }}">
                                <div class="image-wrapper">
                                    <img src="{{ asset('uploads/product/thumbnail/') . '/' . $product->photo }}"
                                        class="card-img-top" alt="{{ $product->productname }}">
                                    <div class="hover-icons d-flex justify-content-center align-items-center">
                                        <form action="{{ route('wishlist.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $product->id ?? 0 }}" name="productid">
                                            <input type="hidden" name="attribute_id" value="{{ $product->attribute_id }}">
                                            <input type="hidden" name="price" value="{{ $price }}">

                                            <a href="#" class="icon-btn me-2" title="Add to Wishlist">
                                                <button type="submit" class="btn">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('front.product_detail', [$product->category_slug, $product->slugname]) }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-semibold">
                                        {{ $product->productname . ' - ' . $product->product_attribute_qty . ' ' . $product->attribute_name }}
                                    </h5>
                                    <p class="fw-bold mb-1 product-price">
                                        <span
                                            class="text-decoration-line-through text-muted">{{ $symbol }}{{ $cut_price }}</span>
                                        {{ $symbol }}{{ $price }}
                                    </p>
                                    <p class="card-text small text-muted">
                                        {{ \Illuminate\Support\Str::words(strip_tags($product->description), 16, '...') }}
                                    </p>

                                    <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="productid" value="{{ $product->id }}">
                                        <input type="hidden" name="categoryId" value="{{ $product->categoryId }}">
                                        <input type="hidden" name="productname" value="{{ $product->productname }}">
                                        <input type="hidden" name="image" value="{{ $product->photo }}">
                                        <input type="hidden" name="attribute_id" value="{{ $product->attribute_id }}">
                                        <input type="hidden" name="attribute_text"
                                            value="{{ $product->product_attribute_qty . ' ' . $product->attribute_name }}">
                                        <input type="hidden" name="price" value="{{ $price }}">
                                        <input type="hidden" name="symbol" value="{{ $symbol }}">
                                        <input type="hidden" name="quantity" value="1">

                                        <a href="#" class="btn-primary-2025 mt-2">
                                            <button class="btn" type="submit">
                                                Add to Cart
                                            </button>
                                        </a>
                                    </form>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>

    <!-- Coming Soon -->
    <section class="section-padding">
        <div class="container">
            <div class="coming-soon-2025" data-aos="fade-up">
                <h2 class="coming-soon-title">Coming Soon</h2>
                <p style="color: white; font-size: 1.1rem;">We're expanding our organic product range to bring you more
                    natural goodness</p>
                <div class="coming-soon-items">
                    <div class="coming-soon-item">
                        <i class="bi bi-circle-fill me-2"></i> Organic Makhana
                    </div>
                    <div class="coming-soon-item">
                        <i class="bi bi-circle-fill me-2"></i> Traditional Spices
                    </div>
                    <div class="coming-soon-item">
                        <i class="bi bi-circle-fill me-2"></i> Herbal Teas
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
