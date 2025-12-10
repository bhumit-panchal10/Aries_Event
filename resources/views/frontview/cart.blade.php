@extends('layouts.front')
@section('title', 'Cart')
@section('content')

    @include('common.frontmodalalert')

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>
        <div class="header-content">
            <h1>Cart</h1>
            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Cart</li>
                </ul>
            </nav>
        </div>
    </section>

    @if (\Cart::isEmpty())
        <div class="col-lg-12 text-center py-5">
            <img src="{{ asset('assets/front/images/no-product.gif') }}" alt="No Products"
                style="max-width: 300px; margin-top: 20px;"> <br>
            <a href="{{ route('front.index') }}" class="btn-primary-2025">Back to Home</a>
        </div>
    @else
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart"
                                style="border: 1px solid var(--primary-green); border-radius:none">
                                <tr class="table_head">
                                    <th class="column-1">Product</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Quantity</th>
                                    <th class="column-5">Total</th>
                                    <th width="10%" class="column-6">Actions</th>
                                </tr>

                                @php
                                    if ($countryCode === 'IN') {
                                        $symbol = '₹';
                                    } else {
                                        $symbol = '$';
                                    }
                                @endphp

                                @foreach ($cartItems as $item)
                                    <tr class="table_row" style="border-bottom: 1px solid var(--primary-green)">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <img src="{{ asset('uploads/product') . '/' . $item->attributes->image }}"
                                                    alt="{{ $item->name }}">
                                            </div>
                                        </td>
                                        <td class="column-2">{{ $item->name }}</td>
                                        <td class="column-3">
                                            {{ $symbol }}{{ $item->price . ' (' . $item->attribute_text . ')' }}
                                        </td>
                                        <td class="column-4">

                                            <div class="quantity-control">
                                                <button onclick="decreaseCount(this, {{ $item->id }})"
                                                    class="qty-btn">−</button>
                                                <input class="qty-input" type="number" readonly name="quantity"
                                                    data-price="{{ $item->price }}" data-symbol="{{ $symbol }}"
                                                    id="quantity_{{ $item->id }}" value="{{ $item->quantity }}">
                                                <button onclick="increaseCount(this, {{ $item->id }})"
                                                    class="qty-btn">+</button>
                                            </div>

                                        </td>
                                        <td class="column-5" id="total_{{ $item->id }}">
                                            {{ $symbol }}{{ $item->price * $item->quantity }}
                                        </td>
                                        <td class="column-6">
                                            <form action="{{ route('cart.remove') }}" method="post"
                                                onsubmit="return confirm('Are you sure you want to remove this item?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" class="btn-delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4">

                        <form class="mb-30" action="{{ route('couponcodeapply') }}" method="post">
                            @csrf
                            <input type="hidden" name="totalAmount" value="{{ \Cart::getTotal() }}">
                            <div class="input-group">
                                <input type="text" name="coupon" class="form-control" placeholder="Coupon Code" required
                                    autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn-primary-2025">Apply Coupon</button>
                                </div>
                            </div>
                        </form>

                        <h3 class=" position-relative text-uppercase my-3 mt-5"><span class=" pr-3">Cart
                                Summary</span></h3>
                        <div class="bg-light p-30 ">
                            <div class=" ">
                                <div class="d-flex justify-content-between  cart-total-value">
                                    <h6>Subtotal</h6>
                                    <h6 style="margin-right: 15px;" id="subtotal">
                                        {{ $symbol }}{{ \Cart::getSubTotal() }}</h6>
                                </div>
                                @if (Session::has('discount'))
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-0">
                                                Coupon <span class="badge badge-pill badge-success btn-primary-2025">
                                                    {{ Session::get('applied_coupon_code') }}
                                                </span>
                                            </h6>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 mr-2 text-danger">-
                                                {{ $symbol }}{{ Session::get('discount') }}</h6>
                                            <form action="{{ route('couponcoderemove') }}" method="post">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-circle p-1"
                                                    title="Remove Coupon">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <div class="">
                                <div class="d-flex justify-content-between  cart-total-value">


                                    <h5>Total</h5>
                                    @php
                                        $subtotal = \Cart::getSubTotal();
                                        $discount = Session::get('discount', 0);
                                        $total = $subtotal - $discount;
                                    @endphp
                                    <h5 style="margin-right: 15px;" id="total">{{ $symbol }}{{ $total }}
                                    </h5>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('front.checkout') }}" class="btn-primary-2025">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


@endsection

@section('scripts')

    <script>
        function increaseCount(a, itemId) {
            var input = document.getElementById('quantity_' + itemId);
            var value = parseInt(input.value, 10);
            value = isNaN(value) ? 0 : value;
            value++;

            updateCart(itemId, value);
        }

        function decreaseCount(a, itemId) {
            var input = document.getElementById('quantity_' + itemId);
            var value = parseInt(input.value, 10);
            if (value > 1) {
                value--;

                updateCart(itemId, value);
            }
        }

        function updateCart(itemId, quantity) {
            let token = '{{ csrf_token() }}';

            fetch("{{ route('cart.update') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({
                        id: itemId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        let input = document.getElementById('quantity_' + itemId);
                        let price = parseFloat(input.getAttribute('data-price')) || 0;
                        let symbol = input.getAttribute('data-symbol');

                        let total = price * quantity;

                        // ✅ update row total with symbol
                        document.getElementById('total_' + itemId).innerText = symbol + total.toFixed(2);

                        // ✅ update quantity input value
                        input.value = quantity;

                        // ✅ update subtotal and total in summary with symbol
                        if (data.cart_summary) {
                            document.getElementById('subtotal').innerText = symbol + data.cart_summary.subtotal.toFixed(
                                2);
                            document.getElementById('total').innerText = symbol + data.cart_summary.total.toFixed(2);
                        }


                    }
                });
        }
    </script>

@endsection
