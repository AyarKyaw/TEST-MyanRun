@extends('layouts.master')

@section('title', 'Race Guide - MYANRUN')

@section('content')
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">race guide</h1>
                    </div><div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li> <i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Race Guide</a></li>
                        </ul>
                    </div></div></div></div></div><section class="main-content blog-posts">
        <div class="themeflat-container">
            
            <div class="pdf-preview">
                @foreach(range(1, 10) as $i)
                    <img src="{{ asset('images/bike/' . $i . '.jpg') }}" alt="Race Guide Page {{ $i }}" style="width: 100%; display: block; margin-bottom: 20px;">
                @endforeach
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