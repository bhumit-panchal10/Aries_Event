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

            <div id="orders-content" class="tab-pane-content">
                <div class="orders-content-wrapper">
                    <h1 class="page-title mb-4">Your Orders</h1>

                    @forelse ($orders as $order)
                        <div class="card order-card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h2 class="order-id">Order #{{ $order->order_id }}</h2>
                                @if ($order->isPayment == 1)
                                    <span class="badge status-paid">Paid</span>
                                @elseif ($order->isPayment == 2)
                                    <span class="badge status-paid">Failed</span>
                                @else
                                    <span class="badge status-paid">Pending</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="product-col">Product</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Size</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col" class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset('uploads/product/' . $item->photo) }}"
                                                                alt="{{ $item->productname }}" class="product-img">
                                                            <span class="ms-3">
                                                                {{ $item->productname }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>₹{{ $item->rate }}</td>
                                                    <td>{{ $item->product_attribute_qty . ' (' . $item->name . ')' }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="text-end">₹{{ $item->rate * $item->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('assets/front/images/no-product.gif') }}" alt="No Orders"
                                style="max-width: 300px;">
                            <p class="mt-3">You have no orders yet.</p>
                            <a href="{{ route('front.index') }}" class="btn-primary-2025">Back to Home</a>
                        </div>
                    @endforelse


                </div>
            </div>
        </div>
    </div>

@endsection
