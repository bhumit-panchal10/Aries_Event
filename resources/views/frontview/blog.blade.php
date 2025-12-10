@extends('layouts.front')
@section('title', 'About Us')
@section('content')

    @include('common.alert')

    <section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
        <div class="header-overlay"></div>

        <div class="header-content">
            <h1>Blog</h1>

            <nav class="bredcrum">
                <ul>
                    <li><a href="{{ route('front.index') }}">Home</a></li>
                    <li>Blog</li>
                </ul>
            </nav>
        </div>
    </section>

    <section id="blog" class="section-padding">
        <div class="container">
            <div class="blog-grid">

                @foreach ($blogs as $blog)
                    <div class="blog-card-2025" data-aos="flip-left" data-aos-delay="200">
                        <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">
                            <div class="blog-image-2025">
                                <img src="{{ asset('uploads/Blog/Thumbnail/' . $blog->strPhoto) }}"
                                    alt="{{ $blog->strTitle }}">
                            </div>
                        </a>
                        <div class="blog-content-2025">
                            <p class="blog-meta-2025">{{ date('M d, Y', strtotime($blog->created_at)) }}</p>
                            <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">
                                <h3 class="blog-title-2025">{{ $blog->strTitle }}</h3>
                            </a>

                            <p class="blog-excerpt-2025">
                                {{ \Illuminate\Support\Str::words(strip_tags($blog->strDescription), 20, '...') }}
                            </p>
                            <a href="{{ route('front.blog_detail', $blog->strSlug) }}" class="blog-link-2025">
                                Read More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="col-lg-3 mx-auto">
                    <div class="pagination-container text-center my-5 ">
                        {{ $blogs->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
