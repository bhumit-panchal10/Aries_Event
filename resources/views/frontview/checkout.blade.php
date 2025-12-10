@extends('layouts.front')
@section('title', 'Checkout')
@section('content')

    <div id="razorpay-gradient-bg"></div>

    <style>
        #razorpay-gradient-bg {
            background: linear-gradient(135deg, #2a7d3e, #8bc34a, #5ebd4b);
            background-size: 200% 200%;
            animation: moveGradient 6s ease infinite;
        }

        @keyframes moveGradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
    @include('common.frontmodalalert')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>

        <div class="header-content">
            <h1>Checkout</h1>

            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Checkout</li>
                </ul>
            </nav>
        </div>
    </section>

    <section class="checkout-container container">
        <div class="row g-4">
            <!-- Billing Details -->
            <form id="checkout-form">

                <div class="col-lg-7">
                    <div class="billing-details">
                        <h4>Billing Details</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Country *</label>
                                <select name="country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->countryName }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone *</label>
                                <input type="text" name="billPhone"
                                    class="form-control @error('billPhone') is-invalid @enderror"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                    maxlength="10" minlength="10" required="required" autocomplete="off"
                                    value="{{ old('billPhone') }}">
                                @error('billPhone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>First Name *</label>
                                <input type="text" name="billFirstName" class="form-control"
                                    value="{{ old('billFirstName') }}" required="required" autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Last Name *</label>
                                <input type="text" name="billLastName" class="form-control"
                                    value="{{ old('billLastName') }}" required="required" autocomplete="off">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Email Address *</label>
                                <input type="email" name="billEmail"
                                    class="form-control @error('billEmail') is-invalid @enderror"
                                    value="{{ old('billEmail') }}" required="required" autocomplete="off">
                                @error('billEmail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Street Address *</label>
                            <input type="text" name="billStreetAddress1" class="form-control mb-2"
                                placeholder="House number and street name" required="required" autocomplete="off"
                                value="{{ old('billStreetAddress1') }}">
                            <input type="text" name="billStreetAddress2" class="form-control"
                                placeholder="Apartment, suite, etc. (optional)" autocomplete="off"
                                value="{{ old('billStreetAddress2') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>State *</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}"
                                    required="required" autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Town / City *</label>
                                <input name="city" type="text" class="form-control" required
                                    value="{{ old('city') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>PIN Code *</label>
                                <input name="pincode" type="text" class="form-control" required
                                    value="{{ old('pincode') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Order Notes (optional)</label>
                            <textarea name="orderNote" class="form-control" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('orderNote') }}</textarea>
                        </div>
                    </div>
                </div>

                @php
                    if ($countryCode === 'IN') {
                        $symbol = '₹';
                    } else {
                        $symbol = '$';
                    }
                @endphp

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="order-summary">

                        <h5>Your Order</h5>
                        <table class="table">
                            <tbody>
                                @php
                                    $cartItems = \Cart::getContent();
                                    $subtotal = \Cart::getSubTotal();
                                    $discount = session('discount', 0);
                                    $grandTotal = $subtotal - $discount;
                                @endphp

                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td> {{ $item->name . ' (' . $item->attribute_text . ')' }}
                                        </td>
                                        <td> Qty : {{ $item->quantity }} </td>
                                        <td>{{ $symbol }}{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td class="fw-bold">Subtotal</td>
                                    <td></td>
                                    <td>{{ $symbol }}{{ number_format($subtotal, 2) }}</td>
                                </tr>

                                @if ($discount > 0)
                                    <tr>
                                        <td>Discount</td>
                                        <td></td>
                                        <td>- {{ $symbol }}{{ number_format($discount, 2) }}</td>
                                    </tr>
                                @endif

                                {{--  <tr>
                                <td class="fw-bold">Shipping</td>
                                <td></td>
                                <td>₹0.00</td>
                            </tr>  --}}

                                <tr>
                                    <td>Total</td>
                                    <td></td>
                                    <td><strong>{{ $symbol }}{{ number_format($grandTotal, 2) }}</strong></td>
                                </tr>

                            </tbody>
                        </table>
                        <button type="submit" class="btn-place-order {{ \Cart::isEmpty() ? 'disabled' : '' }}">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </section>

    <!-- Razorpay Loader Overlay -->
    <div class="overlay" id="overlay"
        style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
        <div class="loader"
            style="border: 8px solid #f3f3f3; border-top: 8px solid #402d52; border-radius: 50%; width: 50px; height: 50px; animation: spin 2s linear infinite;">
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="processingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-4">
                <!--<h4>Thank you!</h4>-->
                <p>Your order is being processed. Please wait...</p>
                <div class="spinner-border text-primary mx-auto" role="status"></div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        function checkcustomer() {

            var phone = $('#billPhone').val();
            var url = "{{ route('front.get_userdata') }}";

            if (phone.length == 10) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        phone: phone,
                    },
                    success: function(data) {
                        console.log(data);

                        $('#billFirstName').val(data.firstname);
                        $('#billLastName').val(data.lastname);
                        $('#billEmail').val(data.customeremail);
                        $('#billStreetAddress1').val(data.address);
                        $('#billStreetAddress2').val(data.address1);
                        $('#billState').val(data.state);

                        $('#shipping_city').val(data.city);
                        // $('#strCountry').val(obj.country);
                        $('#billPinCode').val(data.pincode);
                    }
                });
            }
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        // ✅ CSRF Setup for all AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showLoader() {
            document.getElementById('overlay').style.display = 'flex';
        }

        function hideLoader() {
            document.getElementById('overlay').style.display = 'none';
        }

        $('#checkout-form').submit(function(e) {
            e.preventDefault();
            showLoader();

            // Clear old errors
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');

            $.ajax({
                url: "{{ route('checkoutstore') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {

                        // Show modal
                        $('#processingModal').modal('show');

                        const options = {
                            "key": "{{ config('app.razorpay_key') }}",
                            "amount": response.amount * 100,
                            "currency": response.currency,
                            "order_id": response.razorpay_order_id,
                            "name": "Oro Veda",
                            "description": "Order Payment",
                            "theme": {
                                "color": "#2a7d3e" // your main brand color
                            },
                            "handler": function(r) {
                                $('#razorpay-gradient-bg').fadeOut(300);
                                $.post("{{ route('razprpay.success') }}", {
                                    razorpay_payment_id: r.razorpay_payment_id,
                                    razorpay_order_id: r.razorpay_order_id,
                                    razorpay_signature: r.razorpay_signature,
                                    orderId: response.order_id
                                }, function(res) {
                                    // Use res.id instead of res directly
                                    window.location.href =
                                        "{{ route('razorpay.thankyou', ':id') }}"
                                        .replace(':id', res.id);
                                });
                            },
                            "prefill": {
                                "name": response.customer_name,
                                "email": response.email,
                                "contact": response.mobile
                            },
                            "modal": {
                                ondismiss: function() {
                                    $('#razorpay-gradient-bg').fadeOut(300);
                                }
                            },

                            modal: {
                                ondismiss: function() {
                                    // Hide the processing modal
                                    $('#processingModal').modal('hide');
                                    // Mark payment as failed
                                    $.post("{{ route('razorpay.payment_cancel_by_user') }}", {
                                        orderId: response.order_id,
                                    }, function() {
                                        window.location.href =
                                            "{{ route('razorpay.RazorFail') }}";
                                    }).fail(function() {
                                        hideLoader();
                                    });
                                }
                            }
                        };
                        const rzp = new Razorpay(options);

                        // Show gradient overlay before opening modal
                        $('#razorpay-gradient-bg').fadeIn(300);
                        rzp.open();
                        hideLoader();
                    } else {
                        alert('Something went wrong.');
                        hideLoader();
                    }
                },
                error: function(xhr) {

                    hideLoader();

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {
                            const input = $('[name="' + field + '"]');

                            if (input.length) {
                                input.addClass('is-invalid');

                                // Add error message
                                const errorDiv = $(
                                        '<div class="invalid-feedback d-block"></div>')
                                    .text(messages[0]);

                                input.after(errorDiv);
                            } else {
                                // If field not found, show toast or alert
                                toastr.error(messages[0]);
                            }
                        });
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                }
            });
        });
    </script>

@endsection
