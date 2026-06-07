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
.credentials p{margin:0 0 10px;color:#0f766e;font-size:14px;}
.credentials p:last-child{margin:0;}
.credentials strong{font-family:monospace;font-size:15px;color:#134e4a;}
.steps{padding-left:20px;color:#374151;line-height:1.9;}
.btn-wrap{text-align:center;margin:24px 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;}
.footer{padding:24px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🏃 Welcome to OnePazz Partner Portal</h1>
    </div>
    <div class="body">
        <p>Hi {{ $user->full_name }},</p>
        <p>Your partner account for <strong>{{ $gym->name }}</strong> is ready. Here are your login credentials:</p>
        <div class="credentials">
            <p>🔗 Portal URL: <a href="{{ url('/gym-portal') }}" style="color:#0d9488;">{{ url('/gym-portal') }}</a></p>
            <p>📧 Email: <strong>{{ $user->email }}</strong></p>
            <p>🔑 Temporary Password: <strong>{{ $tempPassword }}</strong></p>
        </div>
        <p style="font-size:13px;color:#dc2626;font-weight:500;">⚠ Please change your password after your first login.</p>
        <div class="btn-wrap">
            <a href="{{ url('/gym-portal') }}" class="btn">Go to Partner Portal →</a>
        </div>
        <p><strong>Getting started:</strong></p>
        <ol class="steps">
            <li>Log in and complete your gym profile</li>
            <li>Upload your cover photo and set operating hours</li>
            <li>Find your QR code under <em>QR Code</em> in the sidebar — members scan this to check in</li>
            <li>Add your classes schedule under <em>Classes</em></li>
            <li>Invite your staff under <em>Staff</em></li>
        </ol>
        <p>Need help? Email us at <a href="mailto:partners@onepazz.com.kh" style="color:#0d9488;">partners@onepazz.com.kh</a>.</p>
        <p>Welcome aboard,<br>The OnePazz Team</p>
    </div>
    <div class="footer">
        <p>OnePazz · Phnom Penh, Cambodia</p>
    </div>
</div>
</body>
</html>
