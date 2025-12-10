@extends('layouts.front')
@section('title', 'Success')
@section('content')

    <div class="overlay" id="overlay">
        <div class="loader"></div>
    </div>

    <style type="text/css">
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


    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url({{ asset('assets/frontimages/catagory/SHOP.jpg') }};">
        <div class="container">
            <!--<h6 class="ltext-105 cl0 txt-center">-->
            <!--	Order Success-->
            <!--</h6>-->
            <div class="bredcrum">
                <ul>
                    <li><a class="text-white" href="{{ route('front.index') }}">Home</a></li>
                    <li><img src="{{ asset('assets/images/breadcrumb.png') }}" alt=""></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Shoping Cart -->
    <div class="bg0" style="min-height:340px; padding:50px 0px 80px 0px;">
        <div class="container ">
            <div class="row">
                <div class="card mx-auto d-block "
                    style="width:350px;border: 1px solid #80563e;padding:30px 10px;border-radius: 10px;">
                    <div class="col-md-12 d-block" style="text-align: center;">
                        <h1 style="border-bottom:1px solid #80563e;">Thank you!</h1>
                    </div>
                    <div class="col-md-12  d-block" style="text-align: center;">
                        <p><br />Thank you for shopping with us. <br /> We will be shipping your order to you soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection
