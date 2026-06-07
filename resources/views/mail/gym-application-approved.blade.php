<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#0d9488;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:22px;}
.body{padding:32px;}
.body p{color:#374151;line-height:1.6;margin:0 0 16px;}
.credentials{background:#f0fdfa;border:1px solid #99f6e4;border-radius:8px;padding:20px;margin:20px 0;}
.credentials p{margin:0 0 8px;color:#0f766e;font-size:14px;}
.credentials p:last-child{margin:0;}
.credentials strong{font-family:monospace;font-size:15px;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;margin:8px 0;}
.footer{padding:24px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🏃 OnePazz</h1>
    </div>
    <div class="body">
        <p>Hi {{ $application->contact_name }},</p>
        <p>🎉 Congratulations! Your application for <strong>{{ $application->studio_name }}</strong> has been <strong>approved</strong>. Welcome to the OnePazz partner network!</p>

        @if($loginEmail && $tempPassword)
        <p>We've created your partner portal account. Here are your login credentials:</p>
        <div class="credentials">
            <p>🔗 Portal: <a href="{{ url('/gym-portal') }}" style="color:#0d9488;">{{ url('/gym-portal') }}</a></p>
            <p>📧 Email: <strong>{{ $loginEmail }}</strong></p>
            <p>🔑 Password: <strong>{{ $tempPassword }}</strong></p>
        </div>
        <p style="font-size:13px;color:#6b7280;">Please change your password after your first login.</p>
        <p style="text-align:center;"><a href="{{ url('/gym-portal') }}" class="btn">Go to Partner Portal →</a></p>
        @endif

        <p><strong>Next steps:</strong></p>
        <ul style="color:#374151;line-height:1.8;padding-left:20px;">
            <li>Log in to your partner portal and complete your gym profile</li>
            <li>Upload photos and set your operating hours</li>
            <li>Your QR code for member check-ins is ready in the portal</li>
        </ul>

        <p>If you need any help getting started, email us at <a href="mailto:partners@onepazz.com.kh" style="color:#0d9488;">partners@onepazz.com.kh</a>.</p>
        <p>Welcome aboard,<br>The OnePazz Team</p>
    </div>
    <div class="footer">
        <p>OnePazz · Phnom Penh, Cambodia</p>
    </div>
</div>
</body>
</html>
