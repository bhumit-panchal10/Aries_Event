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

        /* Spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #8A3C8B;
            /* match Oroveda theme color */
        }
    </style>

    <!-- Loader -->
    <div class="loader-overlay" id="loader">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Verifying OTP...</span>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center my-5">
            <div class="col-md-6 col-lg-4">
                <div class="otp-card">
                    <div class="otp-header">
                        <h2>Enter OTP</h2>
                        <p>We’ve sent a 4-digit code to your registered email.</p>
                    </div>

                    <form method="POST" action="{{ route('front.otp_store') }}" id="otpForm">
                        @csrf

                        {{-- Hidden email field --}}
                        <input type="hidden" name="email" value="{{ session('front_login_email') }}">

                        {{-- Combined OTP input (hidden) --}}
                        <input type="hidden" name="otp" id="otp_value">

                        <div class="otp-inputs d-flex justify-content-between mb-3">
                            <input type="text" maxlength="1" class="otp-field" required>
                            <input type="text" maxlength="1" class="otp-field" required>
                            <input type="text" maxlength="1" class="otp-field" required>
                            <input type="text" maxlength="1" class="otp-field" required>
                        </div>
                        <button type="submit" class="btn btn-verify">Verify OTP</button>
                    </form>

                    <div class="resend-text mt-3">
                        <p>Didn’t receive the code? <a href="{{ route('front.resend_otp') }}">Resend OTP</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const inputs = document.querySelectorAll('.otp-field');
        const otpValue = document.getElementById('otp_value');
        const form = document.getElementById('otpForm');
        const loader = document.getElementById('loader');

        // OTP input logic
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');

                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                otpValue.value = Array.from(inputs).map(i => i.value).join('');
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        // Show loader on form submit
        form.addEventListener('submit', () => {
            loader.style.display = 'flex';
        });

        // Hide loader if page reloads (e.g., validation error)
        window.addEventListener('pageshow', () => {
            loader.style.display = 'none';
        });
    </script>
@endsection
