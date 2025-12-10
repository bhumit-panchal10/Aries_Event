@extends('layouts.front')
@section('title', 'Thank You')

@section('content')

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>
        <div class="header-content">
            <h1>Thank You</h1>
            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Thank You</li>
                </ul>
            </nav>
        </div>
    </section>

    <section class="section-padding thank-you">
        <div class="container-fluid">

            <div class="row px-xl-5 justify-content-between d-flex">
                <div class="col-lg-4 thank-you-img ">
                    <img src="{{ asset('assets/front/images/thankyou.gif') }}" alt="Thank You" class="img-fluid">
                </div>
                <div class="col-lg-6">
                    <h2 class="text-pink">Thank You for Connecting with Oroveda</h2>
                    <p>
                        We appreciate your interest in <strong>Oroveda</strong>. Your message has been received,
                        and our
                        team will get back to you shortly.
                    </p>

                </div>
            </div>
        </div>
    </section>

@endsection
