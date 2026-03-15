@extends('layouts.master')

@section('title', 'Race Guide - MYANRUN')

@section('content')
    <!-- Page Title -->
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">race guide</h1>
                    </div><!-- /.page-title-captions -->
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="index.html">Homepage</a></li>
                            <li> <i class="icon-Arrow---Right-2"></i></li>
                           
                            <li><a>Race Guide</a></li>
                        </ul>
                    </div><!-- /.breadcrumbs -->

                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.page-title -->

    <!-- Blog Posts -->
    <section class="main-content blog-posts">
    <div class="themeflat-container">

        <!-- Tabs -->
        <div class="text-center mb-4">
    <button class="tab-btn active" onclick="showTab('eng')" style="display: inline-flex; align-items: center; gap: 8px;">
        <img src="https://flagcdn.com/w40/gb.png" width="20" alt="UK Flag"> 
        English
    </button>

    <button class="tab-btn" onclick="showTab('mm')" style="display: inline-flex; align-items: center; gap: 8px;">
        <img src="https://flagcdn.com/w40/mm.png" width="20" alt="Myanmar Flag"> 
        Myanmar
    </button>
</div>

        <div id="eng" class="tab-content active">
    <div class="pdf-preview">
        @foreach(range(1, 46) as $i)
            <img src="{{ asset('images/pdf/Eng_page-' . sprintf('%04d', $i) . '.jpg') }}" alt="English PDF Page {{ $i }}">
        @endforeach
    </div>
</div>

<div id="mm" class="tab-content">
    <div class="pdf-preview">
        @foreach(range(1, 46) as $i)
            <img src="{{ asset('images/mpdf/RaceGuide_KBZC10MR26_Myanmar_page-' . sprintf('%04d', $i) . '.jpg') }}" alt="Myanmar PDF Page {{ $i }}">
        @endforeach
    </div>
</div>

    </div>
</section>
@endsection