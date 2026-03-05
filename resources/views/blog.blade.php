@extends('layouts.master')

@section('title', 'Our Stories - MYANRUN')

@section('content')
<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">our stories</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="{{ url('/') }}">Homepage</a></li>
                        <li><i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Our Stories</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="main-content blog-posts">
    <div class="themeflat-container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-9 col-xl-9 widget-blog-content">
                <div class="post-wrap">
                    
                    @forelse($stories as $story)
                        <article class="entry format-standard wow fadeInUp animated">
                            {{-- Story Image --}}
                            <div class="feature-post">
                                <img src="{{ asset($story->image) }}" alt="image">
                            </div>

                            <div class="main-post">
                                <div class="tag">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);">{{ $story->company }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <h2 class="entry-title">
                                    {{-- Removed Str::limit to show full text exactly as you provided --}}
                                    <a href="javascript:void(0);">{!! $story->title !!}</a>
                                </h2>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-5">
                            <h4>No stories found.</h4>
                        </div>
                    @endforelse

                    {{-- Simple Pagination --}}
                    <div class="themesflat-pagination clearfix w-100">
                        {{ $stories->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection