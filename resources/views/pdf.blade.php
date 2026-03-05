<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Myan Run - Official Race Pass</title>
    <style>
        @page { margin: 0px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 40px;
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .ticket-wrapper {
            width: 100%;
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        /* Header Table */
        .brand-strip {
            width: 100%;
            background: #0f172a;
            border-bottom: 4px solid #f97316;
        }

        .brand-content {
            padding: 15px 30px;
            color: #ffffff;
            vertical-align: middle; /* Keeps logo and text aligned */
        }

        .logo-container img {
            height: 30px; /* Adjust this to fit your logo's aspect ratio */
            display: block;
        }

        .event-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .ticket-ref {
            font-size: 11px;
            text-transform: uppercase;
            opacity: 0.8;
            text-align: right;
        }

        /* Hero Section */
        .hero-banner {
            padding: 40px 30px;
            text-align: center;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .category-pill {
            display: inline-block;
            background: #f97316;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .runner-id-large {
            font-size: 80px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            line-height: 1;
        }

        /* Info Grid */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-cell {
            padding: 25px 30px;
            border-bottom: 1px solid #f1f5f9;
        }

        .label {
            font-size: 10px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .value {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
        }

        /* QR Section */
        .qr-table {
            width: 100%;
            padding: 30px;
        }

        .qr-container {
            border: 1px solid #e2e8f0;
            padding: 10px;
            display: inline-block;
        }

        .instruction-box {
            text-align: right;
            vertical-align: middle;
        }

        .instruction-text {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Footer */
        .footer-bar {
            width: 100%;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="ticket-wrapper">
        <table class="brand-strip">
            <tr>
                <td class="brand-content" width="40%">
                    <div class="logo-container">
                        @if($logo)
                            <img src="{{ $logo }}">
                        @else
                            <div class="event-title">MYAN RUN</div>
                        @endif
                    </div>
                </td>
                <td class="brand-content" width="60%">
                    <div class="ticket-ref">OFFICIAL PASS // ID: {{ $ticket->id }}</div>
                </td>
            </tr>
        </table>

        <div class="hero-banner">
            <div class="category-pill">{{ $user->runner_id }}</div>
            <h1 class="runner-id-large">{{ $ticket->category }}</h1>
        </div>

        <table class="data-table">
            <tr>
                <td class="data-cell" width="50%" style="border-right: 1px solid #f1f5f9;">
                    <div class="label">ATHLETE NAME</div>
                    <div class="value">{{ $user->first_name }} {{ $user->last_name }}</div>
                </td>
                <td class="data-cell" width="50%">
                    <div class="label">REGISTRATION DATE</div>
                    <div class="value">{{ $ticket->created_at->format('d M, Y') }}</div>
                </td>
            </tr>
            <tr>
                <td class="data-cell" style="border-right: 1px solid #f1f5f9;">
                    <div class="label">ENTRY STATUS</div>
                    <div class="value" style="color: #10b981;">VERIFIED</div>
                </td>
                <td class="data-cell">
                    <div class="label">FEE PAID</div>
                    <div class="value">{{ $ticket->price }} MMK</div>
                </td>
            </tr>
        </table>

        <table class="qr-table">
            <tr>
                <td width="120">
                    <div class="qr-container">
                        <img src="{{ $qrCode }}" width="100" height="100">
                    </div>
                </td>
                <td class="instruction-box">
                    <div class="label">SECURITY CHECK</div>
                    <p class="instruction-text">Please present this digital pass and a valid Photo ID at the registration desk to collect your Race Kit.</p>
                </td>
            </tr>
        </table>

        <table class="footer-bar">
            <tr>
                <td style="padding: 20px 30px;">
                    <div style="font-size: 10px; font-weight: bold; color: #94a3b8;">OFFICIAL MARATHON ADMITTANCE</div>
                </td>
                <td style="padding: 20px 30px; text-align: right; opacity: 0.2;">
                    <div style="letter-spacing: 2px;">|||||||||||||||||||||</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>