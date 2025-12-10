@extends('layouts.front')
@section('title', '{{ $datas->pagename }}')
@section('content')

    <!-- Breadcrumb Start -->
    <section class="breadcrumb-section">
        <div class="container-fluid ">
            <div class="row py-5">
                <div class="col-5 mx-auto">
                    <h3 class="slogan">Quality Made Trust Delivered
                    </h3>
                    <nav class="breadcrumb  mb-30">
                        <a class="breadcrumb-item text-dark" href="{{ route('FrontIndex') }}">Home</a>
                        <span class="breadcrumb-item active">{{ $datas->pagename }}</span>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb End -->

    <!-- Start Contact -->
    <section class="blog-single section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    {!! $datas->description !!}
                </div>
            </div>
        </div>
    </section>

@endsection
