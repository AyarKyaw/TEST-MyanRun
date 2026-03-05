@extends('layouts.master')

@section('title', 'Contact - MYANRUN')

@section('content')
    <div class="page-title page-title-blog">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Contact Us</h1>
                    </div><!-- /.page-title-captions -->
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="index.html">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            
                            <li><a>Contact Us</a></li>
                        </ul>
                    </div><!-- /.breadcrumbs -->

                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.page-title -->

    <!-- Map Contact us -->
    <div class="map-contact-us">
        <div class="map-contact relative">
            <iframe
                src="https://www.google.com/maps?q=16.832278,96.127333&z=16&output=embed"
                width="100%"
                height="650"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- Map Contact us -->

    <!-- Contact us -->
    <div class="tf-contact-page main-content">
        <div class="themeflat-container">
            <div class="row contact-page">
                <div class="col-md-5">
                    <div class="contact-page-content">
                        <div class="content-page-title">
                            <span class="wow fadeInUp animated">Contact us</span>
                            <!-- <h2 class="wow fadeInUp animated">Get it touch</h2>
                            <p class="post wow fadeInUp animated">In the 14 years since she first graced our screens in
                                Keeping Up With The
                                Kardashians.</p> -->
                        </div>
                         <div class="list-contact">

    <!-- PHONE -->
    <div class="contact">
        <span class="label">Phone:</span>
        <div class="address phone-row">
            <a href="tel:095405026" class="phone-link">09 540 5026</a>
            <span class="separator">|</span>
            <a href="tel:095135324" class="phone-link">09 513 5324</a>
        </div>
    </div>

    <!-- EMAIL -->
    <div class="contact">
        <span class="label">Email:</span>
        <div class="address">
            <a href="mailto:info@myanrun.com" class="contact-link">
                info@myanrun.com
            </a>
        </div>
    </div>

    <!-- LOCATION -->
    <div class="contact">
        <span class="label">Location:</span>
        <div class="address">
            No.68, Htan Ta Pin Street, Aung Myay Thar Si Housing,<br>
            No(1) Quarter ,Kamaryut, Yangon 11041
        </div>
    </div>

</div>

                        <div class="social-contact">
                           <ul class="social-media wow fadeInUp animated">
    <li>
        <a href="http://www.youtube.com/@RUNderfulMyanmar-j9x" target="_blank" rel="noopener noreferrer">
            <i class="icon-youtube"></i>
        </a>
    </li>
    <li>
        <a href="https://www.facebook.com/share/g/1G6ZtYxVfj/" target="_blank" rel="noopener noreferrer">
            <i class="icon-facebook"></i>
        </a>
    </li>
    <li>
        <a href="https://www.facebook.com/share/1CFptZmwGM/" target="_blank" rel="noopener noreferrer">
            <i class="icon-facebook"></i>
        </a>
    </li>
</ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="contact-page-form">
                        <form method="post" id="contactform-page" class="contact-page form-submit" action="https://themesflat.co/"
                            accept-charset="utf-8" novalidate="novalidate">
                            <div class="text-wrap clearfix">
                                <fieldset class="name-wrap">
                                    <input type="text" id="name" class="tb-my-input" name="name" tabindex="1"
                                        placeholder="Your name" value="" size="32" aria-required="true" required="">
                                </fieldset>
                                <fieldset class="email-wrap">
                                    <input type="email" id="email" class="tb-my-input" name="email" tabindex="2"
                                        placeholder="Your email" value="" size="32" aria-required="true" required="">
                                </fieldset>
                                <fieldset class="phone-wrap">
                                    <input type="tel" id="phone" class="tb-my-input" name="phone" tabindex="1"
                                        placeholder="Telephone" value="" size="32" aria-required="true" required="">
                                </fieldset>
                                <fieldset class="age-wrap">
                                    <input type="text" id="age" class="tb-my-input" name="site" tabindex="1"
                                        placeholder="Age" value="" size="32" aria-required="true" required="">
                                </fieldset>
                            </div>
                            <fieldset class="message-wrap">
                                <textarea id="comment-message" name="message" rows="3" tabindex="4"
                                    placeholder="Message" aria-required="true"></textarea>
                            </fieldset>
                            <a name="submit" href="event.html" id="comment-reply"
                                class="flat-button btn-submit-comment"><span>Join our event</span></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact us -->
@endsection