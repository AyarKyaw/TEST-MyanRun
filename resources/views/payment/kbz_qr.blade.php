<title>MyanRun || QR CODE</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">

<div class="payment-container" style="max-width: 450px; margin: 50px auto; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; background: #fff; position: relative;">
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #1a1a1a; margin-bottom: 5px;">Scan to Pay</h3>
        <div style="font-size: 28px; font-weight: bold; color: #f9a01b; margin: 10px 0;">
            {{ number_format($ticket->price) }} MMK
        </div>
        <h1 style="
            font-size: 48px;
            font-weight: 900;
            letter-spacing: 3px;
            color: #333;
            margin-bottom: 10px;
        ">
            MM<span style="color:#f9a01b;">QR</span>
        </h1>
        <div style="margin: 15px 0;">
        <img src="{{ asset('images/MMQR.jpg') }}"
             style="width: 250px; height: 250px;">
    </div>

        <p style="color: #666; font-size: 14px;">Please use your <strong>Payment App</strong> to scan</p>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; display: inline-block; border: 1px solid #eee; position: relative;">
        
        <!-- QR -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $qrString }}" 
            alt="KBZPay QR" 
            style="display: block;">
    </div>

    <div style="margin-top: 25px;">
        <div id="loading-spinner" class="spinner"></div>
        <p id="status-text" style="color: #555; font-size: 18px; margin-top: 15px;">
            Waiting for payment...
        </p>
        <p style="color: #999; font-size: 12px; margin-top: 5px;">
            Order ID: #{{ $ticket->id }}
        </p>
    </div>

    <div id="success-overlay" class="success-overlay">
        <div class="success-content">
            <div class="checkmark-circle">
                <div class="checkmark draw"></div>
            </div>
            <h2 style="margin-top: 20px; color: #28a745;">Payment Successful!</h2>
            <p style="color: #666;">Redirecting to your dashboard...</p>
        </div>
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span style="font-size: 12px; color: #aaa;">Secure Payment Gateway</span>
    </div>
</div>

<style>
    /* Success Overlay Styles */
    .success-overlay {
        display: none;
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.98);
        z-index: 10;
        border-radius: 15px;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    /* Animated Checkmark */
    .checkmark-circle {
        width: 80px; height: 80px;
        border: 4px solid #28a745;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .checkmark {
        font-size: 50px;
        color: #28a745;
    }
    .checkmark:after {
        content: "✓";
    }

    .spinner {
        width: 30px; height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #f9a01b;
        border-radius: 50%;
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    body.waiting-payment {
        background-color: #f4f7f6;
    }
</style>

<script>
    const ticketId =  {{ $ticket->id }};
    const checkUrl = "{{ url('/payment/status/' . ($ticket->id)) }}";
    const redirectUrl = "{{ route('user.dashboard') }}?success=PaymentReceived";

    let elapsed = 0;
    const pollInterval = 3000; 
    const timeoutLimit = 15 * 60 * 1000; 

    const checkPayment = async () => {
        try {
            const response = await fetch(checkUrl);
            const data = await response.json();

            if (data.paid) {
                clearInterval(polling);
                
                // Show Big Success Overlay
                document.getElementById('success-overlay').style.display = "flex";
                
                // Redirect after 3 seconds so they can see the message
                setTimeout(() => window.location.href = redirectUrl, 3000);
            }
        } catch (err) {
            console.log("Checking connection...");
        }

        elapsed += pollInterval;
        if (elapsed >= timeoutLimit) {
            clearInterval(polling);
            document.getElementById('status-text').innerText = "⚠️ Payment timed out.";
            document.getElementById('loading-spinner').style.display = "none";
        }
    };

    const polling = setInterval(checkPayment, pollInterval);
</script>