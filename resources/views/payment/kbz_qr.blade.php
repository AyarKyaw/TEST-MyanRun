<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyanRun || QR CODE</title>
    
    <style>
        * { font-family: Arial, sans-serif; letter-spacing: 0; box-sizing: border-box; }
        
        /* Centers the card perfectly on both desktop monitors and phone screens */
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .spinner {
            width: 12px; height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #17479E;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .success-overlay {
            display: none;
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.98);
            z-index: 20;
            align-items: center; justify-content: center;
        }

        /* Styling for your custom Mobile App Button Component */
        .kbz-app-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background-color: #17479E;
            color: #ffffff !important;
            text-decoration: none !important;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(23, 71, 158, 0.3);
            margin-top: 12px;
            transition: background-color 0.2s;
        }
        .kbz-app-btn:hover {
            background-color: #113575;
        }

        /* 🖥️ DEFAULT ENVIRONMENT RULES (Laptops and Desktops) */
        .mobile-only-action {
            display: none !important; /* Completely hide the button on desktop */
        }
        .desktop-only-logo {
            display: flex !important; /* Keep your exact original branding look */
        }

        /* 📱 RESPONSIVE MEDIA BREAKPOINT (Activates only on Smartphones) */
        @media (max-width: 768px) {
            /* Hides the redundant footer logo on mobile to prevent overlapping elements */
            .desktop-only-logo {
                display: none !important; 
            }
            
            /* Displays the interactive payment button right under the QR box instead */
            .mobile-only-action {
                display: block !important;
            }
        }
    </style>
</head>
<body>

@php
    $mmqrLogo = 'payment/mmqr.png'; 
    $kbzPayLogo = 'kbzpay_blue_icon.jpg'; 
    
    // GUIDELINE CALCULATIONS (Your exact design proportions)
    $cardWidth = 350;
    $cardHeight = ($cardWidth / 20) * 29; 
    
    $marginSide = $cardWidth * 0.125;      
    $headerHeight = $cardHeight * 0.185;   
    $qrAreaHeight = $cardHeight * 0.44;    
    $qrAreaWidth = $cardWidth * 0.75;      
    $lineHeight = $cardHeight * 0.03;      
    
    // Typography
    $nameFontSize = $cardHeight * 0.03;    
    $amountFontSize = $cardHeight * 0.06;  
    $currencyFontSize = $cardHeight * 0.03; 
@endphp

<div class="payment-card" style="
    width: {{ $cardWidth }}px; 
    height: {{ $cardHeight }}px; 
    background: #ffffff; 
    position: relative; 
    border: 1px solid #ddd;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;">

    <div style="height: {{ $headerHeight }}px; position: relative; display: flex; align-items: center; justify-content: center;">
        <div style="position: absolute; width: 100%; height: {{ $lineHeight }}px; background-color: #FBD913; z-index: 1;"></div>
        <div style="z-index: 2; display: flex; align-items: center;">
            <img src="{{ asset('images/' . $mmqrLogo) }}" 
                 style="max-height: {{ $headerHeight * 1.35 }}px; width: auto; object-fit: contain; mix-blend-mode: multiply;">
        </div>
    </div>

    <div style="padding: 0 {{ $marginSide }}px;">
        <div style="font-size: {{ $nameFontSize }}px; color: #333; margin-bottom: 2px; text-transform: uppercase; font-weight: 500;">
            MYAN RUN
        </div>
        <div style="font-weight: bold; color: #000; display: flex; align-items: baseline; gap: 4px; margin-bottom: 10px;">
            <span style="font-size: {{ $amountFontSize }}px;">{{ number_format($ticket->price) }}</span>
            <span style="font-size: {{ $currencyFontSize }}px;">MMK</span>
        </div>

        <div style="text-align: center; font-weight: bold; color: #17479E; font-size: 18px; margin-bottom: 5px;">
            MMQR
        </div>

        <div style="
            width: {{ $qrAreaWidth }}px; 
            height: {{ $qrAreaHeight }}px; 
            margin: 0 auto;
            border: 2px solid #17479E; 
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 10px;
            box-sizing: border-box;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrString) }}" 
                 style="max-width: 100%; max-height: 100%; display: block;">
        </div>

        <div class="mobile-only-action">
            <a href="kbzpay://megapp?url={{ urlencode($qrString) }}" class="kbz-app-btn">
                <img src="{{ asset('images/payment/' . $kbzPayLogo) }}" style="height: 16px; width: auto; vertical-align: middle; margin-right: 8px; border-radius: 3px;">
                Open in KBZPay App
            </a>
        </div>
    </div>

    <div class="desktop-only-logo" style="position: absolute; bottom: 48px; width: 100%; display: flex; justify-content: center;">
        <img src="{{ asset('images/payment/' . $kbzPayLogo) }}" style="height: 35px; width: auto; object-fit: contain;">
    </div>

    <div style="height: {{ $lineHeight }}px; background-color: #17479E; width: 100%; position: absolute; bottom: 0;"></div>

    <div style="position: absolute; bottom: 22px; width: 100%; text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
            <div class="spinner"></div>
            <p id="status-text" style="font-size: 10px; color: #888; margin: 0;">Waiting for payment...</p>
        </div>
    </div>
    
    <div id="success-overlay" class="success-overlay">
        <div style="text-align: center;">
            <div style="font-size: 50px; color: #28a745;">✓</div>
            <h3 style="color: #28a745; margin: 0;">PAID</h3>
        </div>
    </div>
</div>

<script>
    const checkPayment = async () => {
        try {
            const response = await fetch("{{ url('/payment/status/' . $ticket->id) }}");
            const data = await response.json();
            if (data.paid) {
                document.getElementById('success-overlay').style.display = "flex";
                setTimeout(() => window.location.href = "{{ route('user.dashboard') }}", 2500);
            }
        } catch (e) {}
    };
    setInterval(checkPayment, 3000);
</script>

</body>
</html>