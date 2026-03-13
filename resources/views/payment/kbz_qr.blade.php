<title>MyanRun || QR CODE</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">

<div class="payment-container" style="max-width: 450px; margin: 50px auto; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; background: #fff;">
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #1a1a1a; margin-bottom: 5px;">Scan to Pay</h3>
        <p style="color: #666; font-size: 14px;">Please use your <strong>KBZPay App</strong> to scan</p>
    </div>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; display: inline-block; border: 1px solid #eee;">
        {{-- Render QR code from KBZ API --}}
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $qrString }}" 
             alt="KBZPay QR" 
             style="display: block; mix-blend-mode: multiply;">
    </div>

    <div style="margin-top: 25px;">
        <div id="loading-spinner" class="spinner"></div>
        <p id="status-text" style="color: #555; font-size: 15px; margin-top: 15px;">
            Waiting for payment...
        </p>
        <p style="color: #999; font-size: 12px; margin-top: 5px;">
            Order ID: #{{ $ticket->id }}
        </p>
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span style="font-size: 12px; color: #aaa;">Secure Payment Gateway</span>
    </div>
</div>

<style>
    .spinner {
        width: 30px;
        height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #f9a01b; /* MyanRun Orange */
        border-radius: 50%;
        display: inline-block;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    body.waiting-payment {
        overflow: hidden;
        background-color: #f4f7f6;
    }
</style>

<script>
    document.body.classList.add('waiting-payment');

    const ticketId = {{ $ticket->id }};
    const checkUrl = "{{ url('/payment/status/' . $ticket->id) }}";
    const redirectUrl = "{{ route('user.dashboard') }}?success=PaymentReceived";

    let elapsed = 0;
    const pollInterval = 3000; // 3 seconds
    const timeoutLimit = 15 * 60 * 1000; // 15 minutes

    const checkPayment = async () => {
        try {
            const response = await fetch(checkUrl);
            if (!response.ok) throw new Error('Network response not ok');
            const data = await response.json();

            if (data.paid) {
                clearInterval(polling);
                document.getElementById('status-text').innerHTML = "✅ <strong>Payment Successful!</strong>";
                document.getElementById('status-text').style.color = "#28a745";
                document.getElementById('loading-spinner').style.display = "none";
                document.body.classList.remove('waiting-payment');

                setTimeout(() => window.location.href = redirectUrl, 1500);
            }
        } catch (err) {
            console.log("Waiting for connection...", err);
        }

        elapsed += pollInterval;
        if (elapsed >= timeoutLimit) {
            clearInterval(polling);
            document.getElementById('status-text').innerHTML = "⚠️ Payment timed out. Please try again.";
            document.getElementById('status-text').style.color = "#dc3545";
            document.getElementById('loading-spinner').style.display = "none";
            document.body.classList.remove('waiting-payment');
        }
    };

    const polling = setInterval(checkPayment, pollInterval);
</script>