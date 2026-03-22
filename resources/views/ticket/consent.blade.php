@extends('layouts.master')

@section('title', 'Consent - MYANRUN')

@section('content')
    <div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Review & Registration</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="/">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Final Consent</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="main-content" style="padding-top: 80px; padding-bottom: 80px; background-color: #fdfdfd;">
    <div class="themeflat-container">
        <div class="row">
            
            <div class="col-md-12">
                <div class="about-box">
                    <div class="title-box">
                        <span class="sub-title" style="text-transform: uppercase; letter-spacing: 2px; color: #e63946; font-weight: bold; display: block; margin-bottom: 10px;">Verify Your Details</span>
                        <h2 class="title-section" style="font-size: 32px; font-weight: 700; margin-bottom: 30px;">Registration Summary</h2>
                    </div>

                    <div class="post" style="color: #333; line-height: 1.8; font-size: 15px; text-align: justify;">
                        <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 20px;">Terms of Service & Waiver</h3>
                        
                        <p style="background: #f8f9fa; padding: 25px; border-left: 5px solid #333; font-style: italic; margin-bottom: 35px;">
                            "The following Terms of Service constitute a binding legal agreement between you and MyanRun. By completing this registration, you acknowledge that you have read, understood, and agreed to these terms."
                        </p>

                        <h4 style="font-size: 18px; font-weight: 700; margin-top: 30px;">1. PARTICIPATION RULES</h4>
                        <p>I confirm that the information provided for <strong>{{ $data['bib_name'] }}</strong> is correct. I understand that my race bib is non-transferable and that participating under another person's name will result in immediate disqualification.</p>

                        <h4 style="font-size: 18px; font-weight: 700; margin-top: 30px;">2. HEALTH & SAFETY WAIVER</h4>
                        <p>I attest that I am physically fit and sufficiently trained for the <strong>{{ $data['category'] }}</strong>. I voluntarily assume all risks associated with running in this event, including but not limited to falls, contact with other participants, and the effects of weather.</p>

                        <h4 style="font-size: 18px; font-weight: 700; margin-top: 30px;">3. EXTRA OPTIONS & ADD-ONS</h4>
                        <p>I agree to the specific terms regarding <strong>{{ $data['extra_option'] ?? 'all selected extras' }}</strong>. I understand that these selections are final and cannot be modified once the payment is processed.</p>

                        <h4 style="font-size: 18px; font-weight: 700; margin-top: 30px;">4. MEDIA & DATA CONSENT</h4>
                        <p>I grant permission to MyanRun to use my photograph, motion pictures, recordings, or any other record of this event for any legitimate purpose, including event photography verified via Face ID.</p>

                        <p style="margin-top: 40px; color: #777;">[Add any additional legal sections here...]</p>

                        <div id="scroll-finish"></div>
                    </div>

                    <div id="consent-section" style="margin-top: 60px; padding: 40px; border-top: 2px dashed #eee; opacity: 0.2; pointer-events: none; transition: all 0.6s ease;">
                        <form action="{{ route('checkout.review') }}" method="GET">
                            <div style="margin-bottom: 30px;">
                                <label style="display: flex; align-items: flex-start; gap: 15px; cursor: pointer;">
                                    <input type="checkbox" id="agree-check" style="width: 22px; height: 22px; margin-top: 3px; accent-color: #333;">
                                    <span style="font-size: 15px; font-weight: 600; color: #333;">
                                        I have read the terms above and I agree to the conditions for the {{ $data['category'] }} and my selected {{ $data['extra_option'] ?? 'add-ons' }}.
                                    </span>
                                </label>
                            </div>

                            <button type="submit" id="payBtn" disabled 
                                style="padding: 20px 60px; background: #333; color: #fff; border: none; border-radius: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; cursor: not-allowed; transition: all 0.3s;">
                                Proceed to Payment
                            </button>
                        </form>
                    </div>

                    <div id="read-reminder" style="text-align: center; margin-top: 20px; color: #e63946; font-weight: 700; font-size: 13px;">
                        <i class="icon-Arrow---Down-2"></i> PLEASE SCROLL DOWN TO ACCEPT TERMS
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Unlocks the checkbox section when the user scrolls to the bottom of the text
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.getElementById('consent-section').style.opacity = "1";
                document.getElementById('consent-section').style.pointerEvents = "auto";
                document.getElementById('read-reminder').style.display = "none";
            }
        });
    }, { threshold: 1.0 });

    observer.observe(document.getElementById('scroll-finish'));

    // Enable/Disable button based on checkbox
    const check = document.getElementById('agree-check');
    const btn = document.getElementById('payBtn');

    check.onchange = function() {
        if(this.checked) {
            btn.disabled = false;
            btn.style.background = "#e63946"; // Highlights when ready
            btn.style.cursor = "pointer";
        } else {
            btn.disabled = true;
            btn.style.background = "#333";
            btn.style.cursor = "not-allowed";
        }
    };
</script>
@endpush
@endsection