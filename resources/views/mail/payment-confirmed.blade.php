<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 40px 20px;">

<div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">

    {{-- Header --}}
    <div style="background: #0f766e; padding: 24px 32px;">
        <p style="color: white; font-size: 18px; font-weight: 700; margin: 0;">🏃 OnePazz</p>
    </div>

    {{-- Body --}}
    <div style="padding: 32px;">

        {{-- Status badge --}}
        <div style="display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 999px; margin-bottom: 20px;">
            ✓ Payment Confirmed
        </div>

        <h2 style="margin-top: 0; color: #111827; font-size: 20px; font-weight: 700;">Your payment has been confirmed</h2>
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
            Great news! Your bank transfer for <strong>{{ $payment->employer->company_name }}</strong> has been verified and your subscription is now active.
        </p>

        {{-- Details --}}
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 24px 0;">
            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td style="color: #6b7280; padding: 6px 0;">Invoice #</td>
                    <td style="font-family: monospace; color: #111827; text-align: right;">{{ $payment->invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Billing Period</td>
                    <td style="color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">{{ $payment->invoice->billing_period_start->format('M Y') }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Amount Confirmed</td>
                    <td style="font-weight: 700; color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">${{ number_format($payment->amount_usd, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Confirmed On</td>
                    <td style="color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">{{ $payment->confirmed_at?->format('d M Y, H:i') ?? now()->format('d M Y') }}</td>
                </tr>
            </table>
        </div>

        @if($payment->notes)
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 13px; color: #1e40af;">
            <strong>Note from OnePazz:</strong> {{ $payment->notes }}
        </div>
        @endif

        <a href="{{ config('app.url') }}/dashboard"
           style="display: inline-block; background: #0f766e; color: white; text-decoration: none; padding: 11px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            Go to Dashboard →
        </a>
    </div>

    {{-- Footer --}}
    <div style="padding: 16px 32px; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af;">
        OnePazz Co., Ltd · Phnom Penh, Cambodia<br>
        Questions? Contact billing@onepazz.com.kh
    </div>

</div>

</body>
</html>
