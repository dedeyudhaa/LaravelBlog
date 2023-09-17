@extends('front.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Selamat Datang di Laravel Blog')

@section('meta_tags')

    <meta name="title" content="{{ Str::ucfirst($post->post_title) }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="description" content="{{ Str::ucfirst(words($post->post_content, 120)) }}">
    <meta name="author" content="{{ $post->author->username }}">
    <link rel="cannonical" href="{{ route('read_post', $post->post_slug) }}">
    <meta property="og:title" content="{{ Str::ucfirst($post->post_title) }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ Str::ucfirst($post->post_content, 120) }}">
    <meta property="og:url" content="{{ route('read_post', $post->post_slug) }}">
    <meta property="og:image" content="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}">
    <meta name="twitter:domain" content="{{ Request::getHost() }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ Str::ucfirst($post->post_title) }}">
    <meta name="twitter:description" content="{{ Str::ucfirst(words($post->post_content, 120)) }}">
    <meta name="twitter:image" content="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}">

@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8 mb-5 mb-lg-0">
            <article>
                <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ $post->featured_image }}"
                    alt="Post Thumbnail" class="w-100">
                <ul class="post-meta mb-2 mt-4">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            style="margin-right:5px;margin-top:-4px" class="text-dark" viewBox="0 0 16 16">
                            <path d="M5.5 10.5A.5.5 0 0 1 6 10h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"></path>
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z">
                            </path>
                            <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z">
                            </path>
                        </svg> <span>{{ date_formatter($post->created_at) }}</span>
                    </li>
                </ul>
                <h1 class="my-3">{{ $post->post_title }}</h1>
                <ul class="post-meta mb-4">
                    <li> <a
                            href="{{ route('category_posts', $post->subcategory->slug) }}">{{ $post->subcategory->subcategory_name }}</a>
                    </li>
                </ul>
                <div class="content text-left">

                    <p>{!! $post->post_content !!}</p>

                </div>
            </article>

            
            {{-- RELATED POST --}}
            @if ( count($related_posts) > 0 )
                
            <div class="widget-list mt-5">
                <h2 class="mb-2">Related posts</h2>
                
                @foreach ($related_posts as $item)
                
                <a class="media align-items-center" href="{{ route('read_post', $item->post_slug) }}">
                    <img loading="lazy" decoding="async" src="/storage/images/post_images/thumbnails/thumb_{{ $item->featured_image }}" alt="Post Thumbnail" class="w-100">
                    <div class="media-body ml-3">
                        <h3 style="margin-top:-5px">{{ $item->post_title }}</h3>
                        <p class="mb-0 small">{!! Str::ucfirst(words($item->post_content, 25)) !!}</p>
                    </div>
                </a>
                    
                @endforeach
            </div>
            
            @endif


            <div class="mt-5">

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
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Categories</h2>
                            <div class="widget-body">
                                <ul class="widget-list">
                                    @include('front.layouts.inc.categories_list')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('stylesheets')
    <link rel="stylesheet" href="/share_buttons/jquery.floating-social-share.min.css">
@endpush
@push('scripts')
    <script src="/share_buttons/jquery.floating-social-share.min.js"></script>
    <script>
        $("body").floatingSocialShare({
            buttons:[
                "facebook","linkedin","pinterest","reddit","telegram","tumblr","twitter","viber","vk","whatsapp"
            ],
            text:"share with: ",
            url:"{{ route('read_post', $post->post_slug) }}"
        });
    </script>
@endpush
