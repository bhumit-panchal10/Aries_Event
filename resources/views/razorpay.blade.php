@extends('layouts.front')
@section('title', 'Payment')
@section('content')



    <style>
        .ship-head {
            padding: 6px;
            background: #9a7c6f;
            color: white;
            font-size: 16px;
            text-transform: uppercase;

        }

        .ship-inp {
            border: none;
            margin-bottom: 0px;
            width: 100%;
            color: #9a7c6f;
        }

        input {
            color: #9a7c6f !important;
            border: none !important;
        }

        .b-none {
            border: none !important;
        }

        .btn:hover {
            color: #9a7c6f !important;
        }

        table {
            border: 1px solid #9a7c6f;
        }

        td {
            padding: 0px 10px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }


        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #8c563d;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="{{ asset('/front/css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('front/css/bootstrap-4.5.0.min.css') }}" rel="stylesheet">


    <div class="overlay" id="overlay">
        <div class="loader"></div>
    </div>

    <!--<body>-->
    <!--    <div class="container">-->
    <!--        <br>-->
    <!--        <div class="text-center">-->
    <!--            <img src="{{ asset('/assets/frontimages/icons/Kwality.png') }}" class="main-logo" width="128" alt="Kwality" title="Kwality">-->
    <!--        </div>-->
    <!--        <hr>-->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url({{ asset('assets/frontimages/catagory/SHOP.jpg') }});">
        <h2 class="ltext-105 cl0 txt-center">
            <!--Payment-->
        </h2>
    </section>
    <section class="order-summery1 bg-lp p-8">
        <div class="container" style="display:none;">




            <div class="col-lg-12 p-2">
                <h6 class="title showorder">Order Summary &nbsp; &nbsp; <i class="ti-angle-down"></i>&nbsp;<span
                        class="pull-right"> ₹ {{ $Order['netAmount'] }}</span> </h6>
            </div>
            <div class="row">

                <div class="col-lg-12 ">
                    <!-- Shopping Summery -->
                    <table class="table order-table">
                        <thead>
                            <tr class="main-hading">
                                <th class="text-left">PRODUCT</th>
                                <th class="text-right">QTY</th>
                                <th class="text-center">SIZE</th>
                                <th class="text-left">PRICE</th>

                                <th class="text-right">TOTAL</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cartItems = \Cart::getContent();
                            $total = 0;
                            ?>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td class=" text-left" data-title="No"><img
                                            src="{{ asset('Product') . '/' . $item->attributes->image }}" alt="#">
                                    </td>
                                    <!--<td class="product-des" data-title="Description">-->
                                    <!--    <p class="product-name"><a href="productlisting.php">{{ $item->name }}</a></p>-->
                                    <!--</td>-->
                                    <td class=" text-right" data-title="Qty">
                                        {{ $item->quantity }}&nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td class=" text-center" data-title="Qty">
                                        {{ $item->size }}
                                    </td>
                                    <td class="price text-left" data-title="Price">
                                        <span> &#x20B9; {{ $item->price }}
                                        </span>
                                    </td>

                                    <td class="total-amount text-right" data-title="Total">
                                        <span> &#x20B9; {{ $item->price * $item->quantity }}</span>
                                    </td>

                                </tr>
                                <?php $total += $item->price * $item->quantity; ?>
                            @endforeach

                            <!--<tr>-->
                            <!--    <td class="text-right border-0 " colspan="4">Subtotal</td>-->
                            <!--    <td class="text-right border-0">₹ {{ $total }}</td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                            <!--    <td class="text-right border-0" colspan="4">Shipping</td>-->
                            <!--    <td class="text-right border-0">₹ 0.00</td>-->
                            <!--</tr>-->
                            <tr>
                                <td class="text-right bold border-0" colspan="4">Total Amount</td>
                                <td class="bold text-right border-0">₹ {{ $total }}</td>
                            </tr>

                        </tbody>
                    </table>
                    <!--/ End Shopping Summery -->
                </div>
            </div>
        </div>
    </section>
    <div class="row">


        <!--<div class="col-md-4"></div>-->
        <div class="col-md-12" style="margin-top: 15px;">
            <form id="myForm">
                <table width="40%" class="  text-center border-0 m-2" border="0" height="120" align="center">

                    <input type="hidden" id="data-key" value="{{ env('RAZORPAY_KEY') }}">
                    <input type="hidden" id="data-amount" value="{{ $Order['netAmount'] }}">
                    <input type="hidden" id="data-mobile" value="{{ $Order['shipping_mobile'] }}">
                    <input type="hidden" id="data-email" value="{{ $Order['shipping_email'] }}">

                    <input type="hidden" id="data-profile-id" value="{{ $Order['order_id'] }}">
                    <input type="hidden" id="data-description" value="Rozerpay">
                    <input type="hidden" id="data-order-id" value="{{ $orderId }}">


                    <tr>
                        <td class="ship-head " colspan="2">Shipping information </td>
                    </tr>

                    <tr class="mt-2 ">
                        <!--<td style="width: 30%;"> Name </td>-->
                        <td style="padding-top:20px"> {{ $Order['shipping_cutomerName'] }} </td>
                    </tr>
                    <tr>
                        <!--<td> Address </td>-->
                        <td>
                            <?php
                            $address1 = trim($Order['shiiping_address1']);
                            $address2 = trim($Order['shiiping_address2']);
                            $State = App\Models\State::where(['stateId' => $Order['shiiping_state']])->first();
                            ?>
                            <div class="ship-inp" name="full_address" id="full_address" cols="30" rows="7">
                                {{ $address1 . ',' . $address2 . ',' . $Order['shipping_city'] . ' ' . $State->stateName }}
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <!--<td>Pincode </td>-->
                        <td>Pincode : {{ $Order['shipping_pincode'] }} &nbsp; Mobile : {{ $Order['shipping_mobile'] }}
                        </td>
                    </tr>

                    <!--<tr>-->
                    <!--<td>Mobile </td>-->
                    <!--    <td>Mobile : {{ $Order['shipping_mobile'] }}</td>-->
                    <!--</tr>-->
                    <tr class="mb-2" style="padding-top:10px">
                        <!--<td> Email </td>-->
                        <td> {{ $Order['shipping_email'] }} </td>
                    </tr>




                </table>
                <!--<table  width="100%" class="mx-auto   border-0" border="0" height="50" align="center">-->
                <!--     <tr class="">-->
                <!--        <td align="top"><a href="" class="pay_now flex-c-m stext-101 cl0 size-116 bg3  hov-btn3 p-lr-15 trans-04 pointer mb-0 btn  text-white text-center w-100" data-amount="{{ $Order['netAmount'] }}" data-mobile="{{ $Order['shipping_mobile'] }}" data-email="{{ $Order['shipping_email'] }}" data-profile-id="{{ $Order['order_id'] }}" data-order-id="{{ $orderId }}">Pay Now</a> &nbsp; -->
                <!--        </td>-->
                <!--    </tr>-->
                <!--      <tr><td></td></tr>-->
                <!--</table>-->

            </form>
        </div>
        <!-- <figure class="card card-product f_card">

                        <figcaption class="info-wrap">
                            <h4 class="title">{{ $Order->shipping_companyName }}</h4>
                            <div class="label_text_f">
                                <div class="label_text_f_left">
                                    <span><i class="fa fa-user"></i></span>
                                    <p>Name :</p>
                                </div>
                                <div class="label_text_f_right">
                                    <p>{{ $Order['shipping_cutomerName'] }}</p>
                                </div>
                            </div>
                            <div class="label_text_f">
                                <div class="label_text_f_left">
                                <span><i class="fa fa-phone"></i></span>
                                    <p>Mobile :</p>
                                </div>
                                <div class="label_text_f_right">
                                    <p>+91 {{ $Order['shipping_mobile'] }}</p>
                                </div>
                            </div>
                            <div class="label_text_f">
                                <div class="label_text_f_left">
                                <span><i class="fa fa-envelope"></i></span>
                                    <p>E-mail :</p>
                                </div>
                                <div class="label_text_f_right">
                                    <p>{{ $Order['shipping_email'] }}</p>
                                </div>
                            </div>
                            <span class="price-new">₹ {{ $Order['netAmount'] }}</span>

                        </figcaption>
                        <div class="bottom-wrap">
                            <div class="f_btn_bottom">

                                <div class="f_btn_bottom_left">
                                    <a href="{{ route('FrontIndex') }}">Back To Profile</a>
                                </div>
                                <div class="f_btn_bottom_right ">
                                    <a href="" class="pay_now" data-amount="{{ $Order['netAmount'] }}" data-mobile="{{ $Order['shipping_mobile'] }}" data-email="{{ $Order['shipping_email'] }}" data-profile-id="{{ $Order['order_id'] }}" data-order-id="{{ $orderId }}">Pay Now</a>
                                </div>
                            </div>
                        </div>
                    </figure> -->
    </div>
    <!-- col // -->
    </div>
    <!-- row.// -->
    <!--</div>-->
    <!--container.//-->
    <!--<br><br><br>-->
@endsection
@section('scripts')

    <script>
        // Get reference to the overlay
        const overlay = document.getElementById('overlay');

        // Function to show the loader
        function showLoader() {
            overlay.style.display = 'flex'; // Display overlay
        }

        // Function to hide the loader
        function hideLoader() {
            overlay.style.display = 'none'; // Hide overlay
        }

        // Show loader when page loads
        showLoader();

        // Hide loader when page content is fully loaded
        window.addEventListener('load', function() {
            hideLoader();
        });
    </script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            showLoader();
            // $('body').on('click', '.pay_now', function(e) {
            var totalAmount = $("#data-amount").val();
            totalAmount = totalAmount * 100;
            var order_id = $("#data-order-id").val();
            var orderid = $("#data-profile-id").val();
            var mobile = $("#data-mobile").val();
            var email = $("#data-email").val();
            var url = "{{ route('razprpay.success') }}";
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": totalAmount, // 2000 paise = INR 20 order generate ?yes
                "currency": "INR",
                "mobile": mobile,
                "email": email,
                "order_id": order_id,
                "handler": function(response) {
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature: response.razorpay_signature,
                            razorpay_order_id: response.razorpay_order_id,
                            orderId: order_id,
                            orderid: orderid
                        },
                        success: function(msg) {
                            if (msg > 1) {
                                showLoader();
                                // window.location.href = "{{ route('razorpay.thankyou') }}";
                                var url = "{{ route('razorpay.thankyou', ':msg') }}";
                                url = url.replace(":msg", msg);
                                window.location.href = url;
                            } else {
                                window.location.href = "{{ route('razorpay.RazorFail') }}";
                                hideLoader();
                            }

                        },
                        error: function(jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            hideLoader();
                            alert(msg);

                        },
                    });
                },
                "prefill": {
                    "contact": mobile,
                    "email": email,
                },
                "theme": {
                    "color": "#528FF0"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });
        /*document.getElementsClass('buy_plan1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
        }*/
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(".showorder").click(function() {
            $(".order-table").toggle();
        });
    </script>
@endsection
