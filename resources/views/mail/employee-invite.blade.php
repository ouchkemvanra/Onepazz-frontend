<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to KhmerFit</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 40px 20px;">

<div style="max-width: 560px; margin: 0 auto; background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">

    {{-- Header --}}
    <div style="background: #0f766e; padding: 24px 32px;">
        <p style="color: white; font-size: 18px; font-weight: 700; margin: 0;">🏃 KhmerFit</p>
        <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin: 4px 0 0;">Cambodia's Corporate Wellness Platform</p>
    </div>

    {{-- Body --}}
    <div style="padding: 32px;">
        <h2 style="margin-top: 0; color: #111827; font-size: 20px; font-weight: 700;">
            Welcome, {{ $user->full_name }}! 👋
        </h2>
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">
            You've been added to the <strong>{{ $employer->company_name }}</strong> wellness programme on KhmerFit. Your account is ready — sign in below to get started.
        </p>

        {{-- Login Credentials --}}
        <div style="background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px; padding: 20px; margin: 24px 0;">
            <p style="margin: 0 0 12px; font-size: 13px; font-weight: 700; color: #0f766e; text-transform: uppercase; letter-spacing: 0.05em;">Your Login Details</p>
            <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td style="color: #6b7280; padding: 5px 0; width: 120px;">Email</td>
                    <td style="color: #111827; font-weight: 600;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 5px 0; border-top: 1px solid #ccfbf1;">Temp Password</td>
                    <td style="font-family: monospace; font-size: 15px; font-weight: 700; color: #111827; letter-spacing: 0.05em; border-top: 1px solid #ccfbf1;">{{ $tempPassword }}</td>
                </tr>
                <tr>
                    <td style="color: #6b7280; padding: 5px 0; border-top: 1px solid #ccfbf1;">Membership Card</td>
                    <td style="font-family: monospace; color: #0f766e; font-weight: 600; border-top: 1px solid #ccfbf1;">{{ $employee->membership_card_no }}</td>
                </tr>
            </table>
        </div>

        <p style="color: #dc2626; font-size: 13px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 10px 14px; margin-bottom: 24px;">
            🔒 Please change your password after your first login.
        </p>

        <a href="{{ config('app.url') }}/login"
           style="display: inline-block; background: #0f766e; color: white; text-decoration: none; padding: 13px 28px; border-radius: 8px; font-size: 15px; font-weight: 700; letter-spacing: 0.01em;">
            Sign In to KhmerFit →
        </a>

        {{-- How to use --}}
        <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #f3f4f6;">
            <p style="font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px;">Getting started:</p>
            <ol style="font-size: 13px; color: #6b7280; line-height: 1.8; margin: 0; padding-left: 20px;">
                <li>Sign in with the credentials above</li>
                <li>Browse partner gyms at <strong>Gyms</strong></li>
                <li>Show your membership card (<strong>{{ $employee->membership_card_no }}</strong>) at any KhmerFit partner gym</li>
            </ol>
        </div>
    </div>

    {{-- Footer --}}
    <div style="padding: 16px 32px; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af;">
        KhmerFit Co., Ltd · Phnom Penh, Cambodia<br>
        Questions? Contact your company HR or support@khmerfit.com.kh
    </div>

</div>

</body>
</html>
