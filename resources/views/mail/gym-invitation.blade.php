<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:linear-gradient(135deg,#0d9488,#0f766e);padding:40px 32px;text-align:center;}
.header h1{color:#fff;margin:0 0 8px;font-size:24px;}
.header p{color:#99f6e4;margin:0;font-size:14px;}
.body{padding:32px;}
.body p{color:#374151;line-height:1.6;margin:0 0 16px;}
.message{background:#f0fdfa;border-left:4px solid #0d9488;border-radius:0 8px 8px 0;padding:16px;margin:20px 0;}
.message p{margin:0;color:#374151;font-style:italic;}
.btn-wrap{text-align:center;margin:28px 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-weight:600;font-size:15px;}
.expiry{background:#fef9f0;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;text-align:center;}
.expiry p{margin:0;color:#92400e;font-size:13px;}
.footer{padding:24px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🏃 You're Invited to Join KhmerFit</h1>
        <p>Cambodia's corporate wellness platform</p>
    </div>
    <div class="body">
        <p>Hi {{ $application->contact_name }},</p>
        <p>The KhmerFit team would love to have <strong>{{ $application->studio_name ?? 'your fitness studio' }}</strong> on our platform. We connect Cambodia's top gyms and fitness studios with corporate members looking for premium wellness benefits.</p>

        @if($personalMessage)
        <div class="message">
            <p>{{ $personalMessage }}</p>
        </div>
        @endif

        <p>Click the button below to complete your partner application and join our network:</p>
        <div class="btn-wrap">
            <a href="{{ url('/gym-apply/accept/' . $application->invite_token) }}" class="btn">Accept Invitation →</a>
        </div>
        <div class="expiry">
            <p>⏰ This invitation expires on <strong>{{ $application->invite_expires_at->format('d M Y') }}</strong></p>
        </div>
        <br>
        <p style="font-size:13px;color:#6b7280;">If you have questions, contact us at <a href="mailto:partners@khmerfit.com.kh" style="color:#0d9488;">partners@khmerfit.com.kh</a></p>
        <p>The KhmerFit Team</p>
    </div>
    <div class="footer">
        <p>KhmerFit · Phnom Penh, Cambodia<br>If you didn't expect this email, you can safely ignore it.</p>
    </div>
</div>
</body>
</html>
