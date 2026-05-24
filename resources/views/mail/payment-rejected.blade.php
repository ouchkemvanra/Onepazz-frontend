<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Requires Attention</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 40px 20px;">

<div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">

    {{-- Header --}}
    <div style="background: #0f766e; padding: 24px 32px;">
        <p style="color: white; font-size: 18px; font-weight: 700; margin: 0;">🏃 KhmerFit</p>
    </div>

    {{-- Body --}}
    <div style="padding: 32px;">

        {{-- Status badge --}}
        <div style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 999px; margin-bottom: 20px;">
            ✗ Payment Not Confirmed
        </div>

        <h2 style="margin-top: 0; color: #111827; font-size: 20px; font-weight: 700;">Action Required: Payment Update</h2>
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
            We were unable to confirm your bank transfer for invoice <strong>{{ $payment->invoice->invoice_number }}</strong>. Please review the details below and resubmit.
        </p>

        {{-- Rejection Reason --}}
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin: 24px 0;">
            <p style="margin: 0 0 6px; font-size: 13px; font-weight: 600; color: #b91c1c;">Reason:</p>
            <p style="margin: 0; font-size: 14px; color: #7f1d1d;">{{ $reason }}</p>
        </div>

        {{-- Invoice Details --}}
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td style="color: #6b7280; padding: 6px 0;">Invoice #</td>
                    <td style="font-family: monospace; color: #111827; text-align: right;">{{ $payment->invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Amount</td>
                    <td style="font-weight: 700; color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">${{ number_format($payment->amount_usd, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Transfer Reference</td>
                    <td style="font-family: monospace; font-size: 12px; color: #6b7280; text-align: right; border-top: 1px solid #f3f4f6;">{{ $payment->transfer_reference ?? '—' }}</td>
                </tr>
            </table>
        </div>

        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
            Please log in to your billing dashboard and submit a new payment with the correct details.
        </p>

        <a href="{{ config('app.url') }}/dashboard/billing"
           style="display: inline-block; background: #0f766e; color: white; text-decoration: none; padding: 11px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            Go to Billing →
        </a>
    </div>

    {{-- Footer --}}
    <div style="padding: 16px 32px; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af;">
        KhmerFit Co., Ltd · Phnom Penh, Cambodia<br>
        Questions? Contact billing@khmerfit.com.kh
    </div>

</div>

</body>
</html>
