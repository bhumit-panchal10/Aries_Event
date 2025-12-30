@extends('layouts.front')

@section('title', 'Search Results')

@section('content')
    <section class="search-results py-5">
        <div class="container">
            <h4>Search Results for "{{ $headerSearch }}" ({{ $productCount }} results)</h4>

            <div class="row">
                @forelse ($products as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6 pb-1">
                                    <div class="product-item bg-light mb-4">
                                        <div class="product-img position-relative overflow-hidden">
                                            
                                            <a class=""
                                                href="{{ route('product_detail', [$product->category_slug, $product->slugname]) }}">
                                                <img class="img-fluid w-100"
                                                    src="{{ asset('uploads/product/thumbnail/') . '/' . $product->photo }}"
                                                    alt="{{ $product->productname }}"></a>

                                            
                            </div>
                                        <div class="text-center py-4">
                                            <a class="h6 text-decoration-none text-truncate"
                                                href="{{ route('product_detail', [$product->category_slug, $product->slugname]) }}">
                                                {{ $product->productname }}
                                            </a>
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                <h5>₹{{ $product->rate }}</h5> &nbsp;
                                                <h6 class="text-muted ml-2"><del>₹{{ $product->cut_price }}</del></h6>
                                            </div>
                                        </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>No products found.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
