@extends('front.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Selamat Datang di Laravel Blog')
@section('content')

    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 border-bottom border-primary d-inline-block">{{ $category->subcategory_name }}</h1>
        </div>
        <div class="col-lg-8 mb-5 mb-lg-0">
            <div class="row">

                @forelse ($posts as $item)
                    <div class="col-md-6 mb-4">
                        <article class="card article-card article-card-sm h-100">
                            <a href="{{ route('read_post', $item->post_slug) }}">
                                <div class="card-image">
                                    <div class="post-info"> <span
                                            class="text-uppercase">{{ date_formatter($item->created_at) }}</span>
                                        <span
                                            class="text-uppercase">{{ readDuration($item->post_title, $item->post_content) }}
                                            @choice('min|mins', readDuration($item->post_title, $item->post_content)) read</span>
                                    </div>
                                    <img loading="lazy" decoding="async"
                                        src="/storage/images/post_images/thumbnails/resized_{{ $item->featured_image }}"
                                        alt="Post Thumbnail" class="w-100" width="420" height="280">
                                </div>
                            </a>
                            <div class="card-body px-0 pb-0">
                                <ul class="post-meta mb-2">
                                    <li> <a href="#!">travel</a>
                                        <a href="#!">news</a>
                                    </li>
                                </ul>
                                <h2><a class="post-title"
                                        href="{{ route('read_post', $item->post_slug) }}">{{ $item->post_title }}</a></h2>
                                <p class="card-text">{!! Str::ucfirst(words($item->post_content, 15)) !!}</p>
                                <div class="content"> <a class="read-more-btn"
                                        href="{{ route('read_post', $item->post_slug) }}">Read Full
                                        Article</a>
                                </div>
                            </div>
                        </article>
                    </div>

                @empty
                    <span class="text-danger">Belum ada postingan</span>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        {{ $posts->appends(request()->input())->links('custom_pagination') }}
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="widget-blocks">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget">
                            <div class="widget-body">
                                <img loading="lazy" decoding="async" src="/front/images/author.jpg" alt="About Me"
                                    class="w-100 author-thumb-sm d-block">
                                <h2 class="widget-title my-3">Hootan Safiyari</h2>
                                <p class="mb-3 pb-2">Hello, I’m Hootan Safiyari. A Content writter, Developer and Story
                                    teller. Working as a Content writter at CoolTech Agency. Quam nihil …</p> <a
                                    href="about.html" class="btn btn-sm btn-outline-primary">Know
                                    More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Recommended</h2>
                            <div class="widget-body">
                                <div class="widget-list">
                                    <article class="card mb-4">
                                        <div class="card-image">
                                            <div class="post-info"> <span class="text-uppercase">1 minutes read</span>
                                            </div>
                                            <img loading="lazy" decoding="async" src="/front/images/post/post-9.jpg"
                                                alt="Post Thumbnail" class="w-100">
                                        </div>
                                        <div class="card-body px-0 pb-1">
                                            <h3><a class="post-title post-title-sm" href="article.html">Portugal and
                                                    France Now
                                                    Allow Unvaccinated Tourists</a></h3>
                                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                                sed do eiusmod tempor …</p>
                                            <div class="content"> <a class="read-more-btn" href="article.html">Read Full
                                                    Article</a>
                                            </div>
                                        </div>
                                    </article>
                                    <a class="media align-items-center" href="article.html">
                                        <img loading="lazy" decoding="async" src="/front/images/post/post-2.jpg"
                                            alt="Post Thumbnail" class="w-100">
                                        <div class="media-body ml-3">
                                            <h3 style="margin-top:-5px">These Are Making It Easier To Visit</h3>
                                            <p class="mb-0 small">Heading Here is example of hedings. You can use …</p>
                                        </div>
                                    </a>
                                    <a class="media align-items-center" href="article.html"> <span
                                            class="image-fallback image-fallback-xs">No Image Specified</span>
                                        <div class="media-body ml-3">
                                            <h3 style="margin-top:-5px">No Image specified</h3>
                                            <p class="mb-0 small">Lorem ipsum dolor sit amet, consectetur adipiscing …</p>
                                        </div>
                                    </a>
                                    <a class="media align-items-center" href="article.html">
                                        <img loading="lazy" decoding="async" src="/front/images/post/post-5.jpg"
                                            alt="Post Thumbnail" class="w-100">
                                        <div class="media-body ml-3">
                                            <h3 style="margin-top:-5px">Perfect For Fashion</h3>
                                            <p class="mb-0 small">Lorem ipsum dolor sit amet, consectetur adipiscing …</p>
                                        </div>
                                    </a>
                                    <a class="media align-items-center" href="article.html">
                                        <img loading="lazy" decoding="async" src="/front/images/post/post-9.jpg"
                                            alt="Post Thumbnail" class="w-100">
                                        <div class="media-body ml-3">
                                            <h3 style="margin-top:-5px">Record Utra Smooth Video</h3>
                                            <p class="mb-0 small">Lorem ipsum dolor sit amet, consectetur adipiscing …</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (categories())
                        
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Categories</h2>
                            <div class="widget-body">
                                <ul class="widget-list">
                                    @foreach (categories() as $item)
                                        
                                    <li>
                                        <a href="{{ route('category_posts', $item->slug) }}">{{ Str::ucfirst($item->subcategory_name) }}<span class="ml-auto"> ({{ $item->posts->count() }})</span></a>
                                    </li>
                                    
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

@endsection
