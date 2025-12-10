@extends('layouts.front')
@section('title', 'Contact')
@section('content')

    @include('common.contactalert')

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>

        <div class="header-content">
            <h1>Contact Us</h1>

            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Contact Us</li>
                </ul>
            </nav>
        </div>
    </section>


    <div class="contact-section mt-5 mb-5 mx-auto">
        <div class="info-block location-details">
            <div class="block-heading">
                <h3><i class="fa-solid fa-location-dot"></i> Office Address</h3>
            </div>
            <div class="block-content">
                <ul class="address-list">
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <div>
                            <strong>Phone:</strong>
                            <p><a href="tel:+91 9876543210">+91 9876543210</a></p>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <strong>E-Mail:</strong>
                            <p><a href="mailto:info@oroveda.com">info@oroveda.com</a></p>
                        </div>
                    </li>
                    <li>
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <div>
                            <strong>Address:</strong>
                            <p><a href="#">ahmedabad, Gujarat</a></p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="info-block message-form">
            <div class="block-heading">
                <h3><i class="fa-solid fa-pen-to-square"></i> Get In Touch</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('front.contact_us_store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="input-grid">
                        <div class="input-field">
                            <input type="text" name="first_name" placeholder="First Name" required
                                class="form-input @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-field">
                            <input type="text" name="last_name" placeholder="Last Name" required
                                class="form-input @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-grid">
                        <div class="input-field">
                            <input type="email" name="email" placeholder="Email Address" required
                                class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-field">
                            <input type="text" name="subject" placeholder="Subject" required
                                class="form-input @error('subject') is-invalid @enderror" value="{{ old('subject') }}">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="input-field">
                        <textarea name="message" placeholder="Message..." rows="5" required
                            class="form-input @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">

                        <div class="form-group mt-4 mb-4">
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-danger" class="reload" id="reload">
                                    &#x21bb;
                                </button>
                            </div>
                        </div>
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"
                            required>
                        @if ($errors->has('captcha'))
                            <span class="help-block">
                                <strong class="">{{ $errors->first('captcha') }}</strong>
                            </span>
                        @endif
                    </div>

                    <button type="submit" class="send-button">Submit Message</button>
                </form>
            </div>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235014.25776274438!2d72.4149249735986!3d23.02018175513144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1678886450123!5m2!1sen!2sin"
                    width="100%" height="350px" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script type="text/javascript">
        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: 'refresh_captcha',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endsection
