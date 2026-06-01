<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#0d9488;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:20px;}
.body{padding:32px;}
p{color:#374151;line-height:1.6;margin:0 0 14px;font-size:14px;}
.creds{background:#f0fdfa;border:1px solid #99f6e4;border-radius:8px;padding:18px;margin:16px 0;}
.creds p{margin:0 0 8px;color:#0f766e;font-size:14px;}
.creds p:last-child{margin:0;}
.creds strong{font-family:monospace;font-size:15px;color:#134e4a;}
.btn-wrap{text-align:center;margin:22px 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;}
.steps{padding-left:20px;color:#374151;line-height:1.9;font-size:14px;}
.footer{padding:20px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header"><h1>🏃 Welcome to KhmerFit!</h1></div>
    <div class="body">
        <p>Hi {{ $user->full_name }},</p>
        <p>Your KhmerFit employer account for <strong>{{ $employer->company_name }}</strong> has been created. Here are your login credentials:</p>
        <div class="creds">
            <p>🔗 Dashboard: <a href="{{ url('/dashboard') }}" style="color:#0d9488;">{{ url('/dashboard') }}</a></p>
            <p>📧 Email: <strong>{{ $user->email }}</strong></p>
            <p>🔑 Password: <strong>{{ $tempPassword }}</strong></p>
        </div>
        <p style="font-size:13px;color:#dc2626;font-weight:500;">⚠ Please change your password after your first login.</p>
        <div class="btn-wrap">
            <a href="{{ url('/dashboard') }}" class="btn">Go to Dashboard →</a>
        </div>
        <p><strong>Getting started:</strong></p>
        <ol class="steps">
            <li>Complete your company profile</li>
            <li>Invite employees via the Employees section</li>
            <li>Employees get access to all partner gyms based on your plan</li>
            <li>Track usage and costs in Reports</li>
        </ol>
        <p>Need help? <a href="mailto:support@khmerfit.com.kh" style="color:#0d9488;">support@khmerfit.com.kh</a></p>
        <p>Welcome aboard,<br>The KhmerFit Team</p>
    </div>
    <div class="footer"><p>KhmerFit · Phnom Penh, Cambodia</p></div>
</div>
</body>
</html>
