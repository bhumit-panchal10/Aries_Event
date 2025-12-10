@extends('layouts.front')
@section('title', 'Home')
@section('content')

    @include('common.frontmodalalert')

    <!-- Hero Banner -->
    <!-- Revolution Slider Wrapper -->
    <!-- 🌟 HERO SLIDER START -->

    <!-- Hero Slider -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">


        <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item active"
                style="background-image: url('{{ asset('assets/front/images/slide-3.png') }}');">
                <div class="carousel-caption text-start">
                    <h1 class="animate__animated animate__fadeInDown">Oroveda – Pure Gir Cow Ghee</h1>
                    <p class="animate__animated animate__fadeInUp animate__delay-1s">
                        Crafted from the milk of sacred Gir cows, bringing you purity and golden nourishment.
                    </p>
                    <a href="#shop"
                        class="btn btn-warning btn-hero text-dark fw-semibold animate__animated animate__zoomIn animate__delay-2s">
                        Shop Now
                    </a>
                    <a href="#about"
                        class="btn btn-outline-light btn-hero animate__animated animate__zoomIn animate__delay-2s">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url('{{ asset('assets/front/images/slide-2.png') }}');">
                <div class="carousel-caption">
                    <h1 class="animate__animated animate__fadeInDown">Ayurvedic Goodness in Every Spoon</h1>
                    <p class="animate__animated animate__fadeInUp animate__delay-1s">
                        Experience the ancient tradition of health and vitality through A2 Gir Cow Ghee.
                    </p>
                    <a href="#shop"
                        class="btn btn-warning btn-hero text-dark fw-semibold animate__animated animate__zoomIn animate__delay-2s">
                        Shop Now
                    </a>
                    <a href="#about"
                        class="btn btn-outline-light btn-hero animate__animated animate__zoomIn animate__delay-2s">
                        Learn More
                    </a>
                </div>
            </div>


        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    <!-- 🌟 HERO SLIDER END -->



    <!-- Product Highlights -->
    <section class="section-padding art-bg">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Why Our Ghee is Special</h2>
                <p class="section-subtitle text-black" data-aos="fade-up" data-aos-delay="100">
                    Our organic Bilona ghee is crafted with care following traditional methods to preserve its nutritional
                    value and authentic flavor.
                </p>
            </div>

            <!-- USP Grid -->
            <div class="row g-4 ">
                <!-- 100% Organic -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="usp-badge-2025 text-center h-100 d-flex flex-column justify-content-start">
                        <div class="usp-icon-2025 mb-3">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h3 class="usp-title-2025">100% Organic</h3>
                        <p class="usp-text-2025">
                            Made from milk of grass-fed cows raised on organic farms without hormones or antibiotics.
                        </p>
                    </div>
                </div>

                <!-- Bilona Method -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="usp-badge-2025 text-center h-100 d-flex flex-column justify-content-start">
                        <div class="usp-icon-2025 mb-3">
                            <i class="bi bi-droplet-fill"></i>
                        </div>
                        <h3 class="usp-title-2025">Bilona Method</h3>
                        <p class="usp-text-2025">
                            Prepared using the traditional hand-churning Bilona method to preserve nutrients.
                        </p>
                    </div>
                </div>

                <!-- Heart Healthy -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="usp-badge-2025 text-center h-100 d-flex flex-column justify-content-start">
                        <div class="usp-icon-2025 mb-3">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h3 class="usp-title-2025">Heart Healthy</h3>
                        <p class="usp-text-2025">
                            Rich in healthy fats, vitamins A, D, E, and K2 that support cardiovascular health.
                        </p>
                    </div>
                </div>

                <!-- No Additives -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
                    <div class="usp-badge-2025 text-center h-100 d-flex flex-column justify-content-start">
                        <div class="usp-icon-2025 mb-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="usp-title-2025">No Additives</h3>
                        <p class="usp-text-2025">
                            Pure ghee with no preservatives, colors, or artificial flavors.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Why Choose Oroveda -->
    <section class="trust-section section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Why Choose Oroveda</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">We're committed to bringing you the
                purest, most authentic organic ghee while supporting sustainable farming practices.</p>
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="trust-grid">
                        <div class="trust-item-2025 col-lg-12" data-aos="fade-right" data-aos-delay="200">
                            <div class="trust-icon-2025">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="trust-content-2025">
                                <h3>Direct from Farms</h3>
                                <p>We work directly with small organic farms to ensure the highest quality milk and fair
                                    prices
                                    for farmers.</p>
                            </div>
                        </div>
                        <div class="trust-item-2025 col-lg-12" data-aos="fade-right" data-aos-delay="300">
                            <div class="trust-icon-2025">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="trust-content-2025">
                                <h3>Traditional Process</h3>
                                <p>Our ghee is made using age-old techniques passed down through generations of ghee makers.
                                </p>
                            </div>
                        </div>
                        <div class="trust-item-2025 col-lg-12" data-aos="fade-right" data-aos-delay="400">
                            <div class="trust-icon-2025">
                                <i class="bi bi-recycle"></i>
                            </div>
                            <div class="trust-content-2025">
                                <h3>Sustainable Packaging</h3>
                                <p>We use eco-friendly glass jars and minimal packaging to reduce our environmental
                                    footprint.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <iframe width="100%" height="415"
                        src="https://www.youtube.com/embed/59w2G_L1OMo?si=wlb0-gjIqAqQKSvW" title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>

        </div>
    </section>

    <!-- All Products Section -->


    <section id="products" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Ghee Collection</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Explore our complete range of premium gir cow ghee products.
                </p>
            </div>

            <div class="row g-4 product-category">

                @foreach ($featuredProduct as $products)
                    @php
                        if ($countryCode === 'IN') {
                            $price = $products->rate; // INR price
                            $cut_price = $products->cut_price; // INR price
                            $symbol = '₹';
                        } else {
                            $price = $products->usd_rate; // USD price
                            $cut_price = $products->usd_cut_price; // INR price
                            $symbol = '$';
                        }
                    @endphp

                    <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">

                        <div class="card h-100 shadow-sm border-0 product-card">
                            <a href="{{ route('front.product_detail', [$products->category_slug, $products->slugname]) }}">
                                <div class="image-wrapper">
                                    <img src="{{ asset('uploads/product/thumbnail/' . $products->photo) }}"
                                        class="card-img-top" alt="{{ $products->productname }}">
                                    <div class="hover-icons d-flex justify-content-center align-items-center">
                                        <form action="{{ route('wishlist.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $products->id ?? 0 }}" name="productid">
                                            <input type="hidden" name="price" value="{{ $price }}">
                                            <input type="hidden" name="attribute_id"
                                                value="{{ $products->attribute_id }}">
                                            <a href="#" class="icon-btn me-2">
                                                <button type="submit" class="btn" title="Add to Wishlist">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                            </a>
                                        </form>

                                        {{--  <a href="#" class="icon-btn" title="Add to Cart"><i
                                            class="bi bi-cart3"></i></a>  --}}
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('front.product_detail', [$products->category_slug, $products->slugname]) }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-semibold">
                                        {{ $products->productname . ' -' . $products->product_attribute_qty . ' ' . $products->attribute_name }}
                                    </h5>
                                    <p class="fw-bold mb-1 product-price">
                                        <span
                                            class="text-decoration-line-through text-muted">{{ $symbol }}{{ $cut_price }}</span>
                                        {{ $symbol }}{{ $price }}
                                    </p>
                                    <p class="card-text small text-muted">
                                        {{ \Illuminate\Support\Str::words(strip_tags($products->description), 20, '...') }}
                                    </p>
                                    {{--  <a href="#" class="btn-primary-2025 mt-2">Add to Cart</a>  --}}
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

    <!-- Testimonials Carousel -->
    <section class="trust-section bg-gradient-to-b from-amber-50 to-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="section-title aos-init aos-animate" data-aos="fade-up" data-aos-duration="800">
                What Our Customers Say
            </h2>
            <p class="text-amber-700 max-w-2xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="100"
                data-aos-duration="900">
                Join thousands of satisfied customers who have made Oroveda a part of
                their healthy lifestyle.
            </p>

            <!-- Swiper -->
            <div class="swiper testimonial-swiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">

                    @foreach ($Testimonial as $testimonial)
                        <div class="swiper-slide">
                            <div
                                class="testimonial-card bg-white/60 backdrop-blur-lg shadow-xl rounded-2xl p-6 hover:scale-[1.02] transition-transform duration-300">
                                <div class="flex justify-center mb-4 text-amber-500">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="italic text-gray-700 mb-6">
                                    {!! $testimonial->description !!}
                                </p>
                                <div class="flex items-center justify-center gap-4">
                                    <img src="{{ asset('uploads/testimonial/' . $testimonial->photo) }}"
                                        alt="{{ $testimonial->name }}" class="w-14 h-14 rounded-full object-cover" />
                                    <div>
                                        <h4 class="font-semibold text-amber-900">{{ $testimonial->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $testimonial->designation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Swiper Controls -->
                <div class="swiper-pagination mt-6"></div>
            </div>
        </div>
    </section>


    <!-- Blog Preview -->
    <section id="blog" class="section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">From Our Blog</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Learn about the benefits of organic ghee,
                traditional recipes, and healthy living tips.</p>

            <div class="blog-grid">

                @foreach ($blogs as $blog)
                    <div class="blog-card-2025" data-aos="flip-left" data-aos-delay="200">
                        <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">
                            <div class="blog-image-2025">
                                <img src="{{ asset('uploads/Blog/Thumbnail/' . '/' . $blog->strPhoto) }}"
                                    alt="{{ $blog->strTitle }}">
                            </div>
                        </a>
                        <div class="blog-content-2025">
                            <p class="blog-meta-2025"> {{ date('M d, Y', strtotime($blog->created_at)) }} </p>
                            <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">
                                <h3 class="blog-title-2025">
                                    {{ \Illuminate\Support\Str::words(strip_tags($blog->strTitle), 8, '...') }}
                                </h3>
                            </a>
                            <p class="blog-excerpt-2025">
                                {{ \Illuminate\Support\Str::words(strip_tags($blog->strDescription), 20, '...') }}
                            </p>
                            <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">Read More
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="section-padding">
        <div class="container">
            <div class="newsletter-2025" data-aos="fade-up">
                <div class="newsletter-content">
                    <h2 class="newsletter-title">Stay Connected</h2>
                    <p class="newsletter-subtitle">Subscribe to our newsletter for exclusive offers, health tips, and
                        new product announcements.</p>
                    <form class="newsletter-form-2025">
                        <div class="input-group">
                            <input type="email" class="form-control form-control-2025"
                                placeholder="Enter your email address" required>
                            <button class="btn btn-primary-2025" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')


@endsection
