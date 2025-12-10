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

            <div id="wishlist-content" class="tab-pane-content">
                <div class="orders-content-wrapper">
                    <h1 class="page-title mb-4">Your Wishlist</h1>

                    @if ($wishlist->count())
                    @foreach ($wishlist as $list)
                    <div class="wishlist-item card mb-3">
                        <div class="card-body d-flex align-items-center">
                            <img src="{{ asset('uploads/product/' . $list->photo) }}" class="wishlist-item-img" alt="{{ $list->productname }}">
                            <div class="wishlist-item-details flex-grow-1 ms-3">
                                <h5 class="wishlist-item-title">{{ $list->productname . ' (' . $list->product_attribute_qty .' '. $list->name . ')' }}</h5>                                
                                <div class="price-info">
                                    <span class="current-price">₹{{ $list->rate }}</span>
                                </div>
                            </div>
                            <form action="{{ route('wishlist.delete') }}" method="post"
                                                onsubmit="return confirm('Are you sure you want to remove this item?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $list->wishlist_id }}">
                            <button class="btn btn-outline-danger delete-btn" aria-label="Remove from wishlist">
                                <i class="bi bi-trash"></i>
                            </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('assets/front/images/no-product.gif') }}" alt="No Products"
                                        style="max-width: 300px;">
                                    <p class="mt-3">Your wishlist is empty.</p>
                                    <a href="{{ route('front.index') }}" class="btn-primary-2025">Back to Home</a>
                                </div>
                            @endif

                </div>
            </div>
            
        </div>
    </div>

@endsection

@section('scripts')

@endsection
