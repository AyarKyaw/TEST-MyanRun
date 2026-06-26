@extends('layouts.master')

@section('title', 'Race Guide - MYANRUN')

@section('content')
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">race guide</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li> <i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Race Guide</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="main-content blog-posts">
        <div class="themeflat-container">
            
            <div class="pdf-preview">
                @if(isset($raceGuides) && !empty($raceGuides))
                    @foreach($raceGuides as $index => $path)
                        <img src="{{ asset($path) }}" alt="Race Guide Page {{ $index + 1 }}" style="width: 100%; display: block; margin-bottom: 20px;">
                    @endforeach
                @else
                    <div style="text-align: center; padding: 50px 20px; color: #666;">
                        <i class="icon-Book" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                        <p style="font-size: 18px; font-weight: bold;">The Race Guide is currently unavailable.</p>
                        <p style="font-size: 14px;">Please check back soon or contact the event administrators.</p>
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection

@push('scripts')
<style>
    .pdf-preview img {
        max-width: 100%;
        height: auto;
        border: 1px solid #eee;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush