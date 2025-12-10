@extends('layouts.front')
@section('title', 'Payment Failed')

@section('content')

    <!-- Payment Fail Message Section -->
    <section class="section-padding bg-light text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <img src="{{ asset('assets/front/images/payment_fail.png') }}" alt="Payment Failed" class="img-fluid mb-4"
                        style="max-width: 320px;">

                    <h2 class="text-danger mb-3">Oops! Your Payment Didn't Go Through</h2>
                    <p class="lead text-muted mb-4">
                        We appreciate your interest in shopping with us. Unfortunately, your transaction could not be
                        completed.
                    </p>
                    <p class="text-muted mb-5">
                        This could be due to a network issue, cancelled transaction, or insufficient balance.
                    </p>

                    <a href="{{ route('cart.list') }}" class="btn-primary-2025 mx-2">Back to Cart</a>
                    <a href="{{ route('front.index') }}" class="btn-primary-2025 mx-2">Continue Shopping</a>

                </div>
            </div>
        </div>
    </section>

@endsection
