@extends('layouts.front')
@section('title', 'Login')

@section('content')

    @include('common.frontmodalalert')

    <style>
        /* Loader overlay */
        .loader-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
        }

        /* Spinner style */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #8A3C8B;
            /* match your Oroveda theme */
        }
    </style>

    <div class="loader-overlay" id="loader">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Sending OTP...</span>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-md-6 col-lg-5">
                <div class="login-card text-center">
                    <div class="login-header mb-4">
                        <h2>Welcome to Oroveda</h2>
                        <p>Enter your email to get OTP in your mail</p>
                    </div>
                    <form method="post" action="{{ route('front.login_store') }}" id="otpForm">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter your email" required>
                        </div>
                        <button type="submit" class="btn btn-login">Get OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('otpForm');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', () => {
            // Show loader when form submits
            loader.style.display = 'flex';
        });

        // Optional: hide loader if page reloads with an error
        window.addEventListener('pageshow', () => {
            loader.style.display = 'none';
        });
    </script>

@endsection
