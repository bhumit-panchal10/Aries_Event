@extends('layouts.front')

@section('title', 'Product Detail')

@section('content')

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

    <section id="product-detail" class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <!-- Product Image Gallery -->
                <div class="col-md-6" data-aos="fade-right">
                    <div id="productGallery" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">

                            @foreach ($Photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('uploads/product/thumbnail/' . $photo->strphoto) }}"
                                        class="d-block w-100 rounded shadow-sm" alt="Product Image {{ $index + 1 }}">
                                </div>
                            @endforeach

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productGallery"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productGallery"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>

                @php
                    // decide which base symbol and product-level price to show
                    $isIN = $countryCode === 'IN';
                    $symbol = $isIN ? '₹' : '$';
                @endphp

                <!-- Product Info -->
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="fw-bold mb-3 product-title">{{ $ProductDetail->productname }}</h2>
                    <p class="fs-4 fw-semibold mb-3 product-price">
                        {{ $symbol }}
                        <span id="product-price">{{ $isIN ? $ProductDetail->rate : $ProductDetail->usd_rate }}</span>
                    </p>

                    @php
                        $selectedAttrId = $ProductDetail->min_attr_id ?? null;
                    @endphp

                    <div class="d-flex align-items-center mb-4">
                        <label for="size" class="form-label me-3 fw-semibold">Size</label>
                        <select id="attribute-select" name="size" class="form-select w-25 me-3" required>
                            @foreach ($attributes as $attribute)
                                @php
                                    // attribute price in INR from DB
                                    $attrInr = (float) $attribute->product_attribute_price;

                                    // if visitor is IN -> use INR attribute price directly
                                    if ($isIN) {
                                        $attrPriceForView = number_format($attrInr, 2, '.', '');
                                    } else {
                                        // For non-IN visitors:
                                        // If your attribute has its own USD value (e.g., $attribute->product_attribute_usd_price) use it.
                                        // Otherwise convert using product-level ratio passed from controller.
                                        if (
                                            isset($attribute->product_attribute_usd_price) &&
                                            $attribute->product_attribute_usd_price != ''
                                        ) {
                                            $attrPriceForView = number_format(
                                                (float) $attribute->product_attribute_usd_price,
                                                2,
                                                '.',
                                                '',
                                            );
                                        } elseif ($conversionRatio > 0) {
                                            $attrPriceForView = number_format($attrInr * $conversionRatio, 2, '.', '');
                                        } else {
                                            // fallback to INR price if conversion unavailable
                                            $attrPriceForView = number_format($attrInr, 2, '.', '');
                                        }
                                    }
                                @endphp

                                <option value="{{ $attribute->id }}" data-price="{{ $attrPriceForView }}"
                                    data-text="{{ $attribute->product_attribute_qty . ' ' . $attribute->attribute_name }}"
                                    {{ (int) $attribute->id === (int) $selectedAttrId ? 'selected' : '' }}>
                                    {{ $attribute->product_attribute_qty . ' ' . $attribute->attribute_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Quantity + Add to Cart -->
                    <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-center mb-4">
                            <label for="quantity" class="me-3 fw-semibold">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" class="form-control w-25 me-3"
                                value="1" min="1">



                            <input type="hidden" name="productid" value="{{ $ProductDetail->id }}">
                            <input type="hidden" name="categoryId" value="{{ $ProductDetail->categoryId }}">
                            <input type="hidden" name="productname" value="{{ $ProductDetail->productname }}">
                            <input type="hidden" name="image" value="{{ $ProductDetail->photo }}">
                            <input type="hidden" name="attribute_id" id="selected-attribute-id" value="">
                            <input type="hidden" name="price" id="hidden-price"
                                value="{{ $isIN ? $ProductDetail->rate : $ProductDetail->usd_rate }}">
                            <input type="hidden" name="symbol" value="{{ $symbol }}">
                            <input type="hidden" name="attribute_text" id="selected-attribute-text" value="">

                            <button class="btn-primary-2025 text-white px-4" type="submit">
                                Add to Cart
                            </button>
                            {{--  <a href="#" class="btn-primary-2025 text-white px-4">Add to Cart</a>  --}}
                        </div>
                    </form>

                    <!-- Short Highlights -->
                    <ul class="list-unstyled text-muted">
                        <li>✔️ 100% Grass-Fed Cow Ghee</li>
                        <li>✔️ Traditional Bilona Process</li>
                        <li>✔️ No Preservatives or Additives</li>
                        <li>✔️ Naturally Rich Aroma & Flavor</li>
                    </ul>

                </div>
            </div>

            <!-- Tabs Section -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc"
                                type="button" role="tab">
                                Description
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content py-4" id="productTabContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="desc" role="tabpanel">
                            <p>
                                {!! $ProductDetail->description !!}
                            </p>
                        </div>

                    </div>
                </div>
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

@section('scripts')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="assets/js/scripts.js" type="text/javascript"></script>

    <script>
        // Restart animation when slide changes
        const carousel = document.querySelector('#heroCarousel');
        carousel.addEventListener('slide.bs.carousel', (e) => {
            const animElems = e.relatedTarget.querySelectorAll('.animate__animated');
            animElems.forEach(el => {
                el.classList.remove('animate__fadeInDown', 'animate__fadeInUp', 'animate__zoomIn');
                void el.offsetWidth; // trigger reflow
                el.classList.add('animate__fadeInDown', 'animate__fadeInUp', 'animate__zoomIn');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".testimonial-swiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
            },
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // set initial UI values from server-rendered option
            const initialOption = $('#attribute-select option:selected');
            const initPrice = parseFloat(initialOption.data('price')) || 0;
            $('#product-price').text(initPrice.toFixed(2));
            $('#hidden-price').val(initPrice.toFixed(2));
            $('#selected-attribute-id').val(initialOption.val());
            $('#selected-attribute-text').val(initialOption.data('text'));

            // on change, read the already-correct price from data-price
            $('#attribute-select').on('change', function() {
                const selected = $(this).find(':selected');
                const price = parseFloat(selected.data('price')) || 0;
                $('#product-price').text(price.toFixed(2));
                $('#hidden-price').val(price.toFixed(2));
                $('#selected-attribute-id').val(selected.val());
                $('#selected-attribute-text').val(selected.data('text'));
            });
        });
    </script>




@endsection
