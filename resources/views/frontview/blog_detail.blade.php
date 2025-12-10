@extends('layouts.front')

@section('opTag')
@section('title', "$Blog->metaTitle")
<meta name="description" content="{{ $Blog->metaDescription }}" />
<meta name="keywords" content="{{ $Blog->metaKeyword }} " />
<?php echo $Blog->head; ?>
<?php echo $Blog->body; ?>
@endsection

@section('content')

<section class="page-header" style="background: linear-gradient(135deg, #2a7d3e, #8bc34a)">
    <div class="header-overlay"></div>

    <div class="header-content">
        <h1>Blog Detail</h1>
        <nav class="bredcrum">
            <ul>
                <li><a href="{{ route('front.index') }}">Home</a></li>
                <li>Blog Detail</li>
            </ul>
        </nav>
    </div>
</section>



<main class="blog-page-section">
    <div class="container">
        <div class="row">

            <div class="col-8">
                <article class="blog-post-card">
                    <header class="blog-header">
                        <h1 class="section-title text-start">{{ $Blog->strTitle }}</h1>
                        <p class="blog-meta">Posted on {{ date('M d, Y', strtotime($Blog->created_at)) }}</p>
                    </header>

                    <img src="{{ asset('uploads/Blog/Thumbnail/' . $Blog->strPhoto) }}" alt="{{ $Blog->strTitle }}"
                        class="featured-image">

                    <div class="blog-content">
                        {!! $Blog->strDescription !!}
                    </div>
                </article>
            </div>

            <div class="col-4">
                <aside class="sidebar-card">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <div class="recent-posts-list">

                        @foreach ($RecentBlog as $blogs)
                            <a href="{{ route('front.blog_detail', $blogs->strSlug) }}" class="recent-post-item">
                                <img src="{{ asset('uploads/Blog/Thumbnail/' . '/' . $blogs->strPhoto) }}"
                                    alt="{{ $blogs->strTitle }}" class="recent-post-thumb">
                                <div class="recent-post-content">
                                    <p class="recent-post-title">{{ $blogs->strTitle }}</p>
                                    <span class="recent-post-date">{{ date('M d, Y', strtotime($Blog->created_at)) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </aside>
            </div>
        </div>
    </div>
</main>


@endsection
