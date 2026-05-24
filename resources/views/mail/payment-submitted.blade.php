<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Submitted</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 40px 20px;">

<div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">

    {{-- Header --}}
    <div style="background: #0f766e; padding: 24px 32px;">
        <p style="color: white; font-size: 18px; font-weight: 700; margin: 0;">🏃 KhmerFit Admin</p>
    </div>

    {{-- Body --}}
    <div style="padding: 32px;">
        <h2 style="margin-top: 0; color: #111827; font-size: 20px; font-weight: 700;">New Payment Submitted</h2>
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
            A bank transfer payment has been submitted by <strong>{{ $employer->company_name }}</strong> and is awaiting your review.
        </p>

        {{-- Details --}}
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 24px 0;">
            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td style="color: #6b7280; padding: 6px 0;">Company</td>
                    <td style="font-weight: 600; color: #111827; text-align: right;">{{ $employer->company_name }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Invoice #</td>
                    <td style="font-family: monospace; color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Billing Period</td>
                    <td style="color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">{{ $invoice->billing_period_start->format('M Y') }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Amount (USD)</td>
                    <td style="font-weight: 700; color: #111827; text-align: right; border-top: 1px solid #f3f4f6;">${{ number_format($invoice->total_usd, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 6px 0; border-top: 1px solid #f3f4f6;">Amount (KHR)</td>
                    <td style="color: #6b7280; text-align: right; border-top: 1px solid #f3f4f6;">{{ number_format($invoice->total_khr) }} ៛</td>
                </tr>
            </table>
        </div>

        <a href="{{ config('app.url') }}/admin/payments"
           style="display: inline-block; background: #0f766e; color: white; text-decoration: none; padding: 11px 22px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            Review Payment →
        </a>
    </div>

    {{-- Footer --}}
    <div style="padding: 16px 32px; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af;">
        KhmerFit Co., Ltd · Phnom Penh, Cambodia<br>
        This is an automated notification. Do not reply to this email.
    </div>

</div>

</body>
</html>
