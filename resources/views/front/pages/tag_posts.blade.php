@extends('front.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Selamat Datang di Laravel Blog')
@section('content')

    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 border-bottom border-primary d-inline-block">{{ $pageTitle }}</h1>
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
                                    <li> <a
                                            href="{{ route('category_posts', $item->subcategory->slug) }}">{{ $item->subcategory->subcategory_name }}</a>
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
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Latest posts</h2>
                            <div class="widget-body">
                                <div class="widget-list">
                                    @foreach (latest_sidebar_posts() as $item)
                                        
                                    <a class="media align-items-center" href="{{ route('read_post', $item->post_slug) }}">
                                        <img loading="lazy" decoding="async" src="/storage/images/post_images/thumbnails/thumb_{{ $item->featured_image }}" alt="Post Thumbnail" class="w-100">
                                        <div class="media-body ml-3">
                                            <h3 style="margin-top:-5px">{{ $item->post_title }}</h3>
                                            <p class="mb-0 small">{!! Str::ucfirst(words($item->post_content, 15)) !!}</p>
                                        </div>
                                    </a>
                                    
                                    @endforeach
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
                                    @include('front.layouts.inc.categories_list')
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    @endif

                    @if (all_tags() != null)
                    @php
                        $allTagsString = all_tags();
                        $allTagsArray = explode(',',$allTagsString);
                    @endphp    
                    <div class="col-lg-12 col-md-6">
                        <div class="widget">
                            <h2 class="section-title mb-3">Tags</h2>
                            <div class="widget-body">
                                <ul class="widget-list">
                                    @foreach (array_unique($allTagsArray) as $tag)
                                        
                                    <li><a href="{{ route('tag_posts',$tag) }}">#{{ $tag }}</a>
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
