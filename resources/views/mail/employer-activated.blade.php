<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:linear-gradient(135deg,#0d9488,#0f766e);padding:36px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:22px;}
.body{padding:32px;}
p{color:#374151;line-height:1.6;margin:0 0 14px;font-size:14px;}
.btn-wrap{text-align:center;margin:24px 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:13px 32px;border-radius:8px;font-weight:600;font-size:15px;}
.steps{padding-left:20px;color:#374151;line-height:1.9;font-size:14px;}
.footer{padding:20px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header"><h1>🎉 Your KhmerFit Account is Active!</h1></div>
    <div class="body">
        <p>Hi {{ $employer->contact_name }},</p>
        <p>Great news! Your payment has been verified and your KhmerFit account for <strong>{{ $employer->company_name }}</strong> is now <strong>active</strong>.</p>
        <div class="btn-wrap">
            <a href="{{ url('/login') }}" class="btn">Log In to Your Dashboard →</a>
        </div>
        <p><strong>Getting started:</strong></p>
        <ol class="steps">
            <li>Log in and complete your company profile</li>
            <li>Invite your employees under <em>Employees</em> in the dashboard</li>
            <li>Employees can browse and check in to partner gyms with the KhmerFit app</li>
            <li>Track usage and costs under <em>Reports</em></li>
        </ol>
        <p>Need help? Email us at <a href="mailto:support@khmerfit.com.kh" style="color:#0d9488;">support@khmerfit.com.kh</a>.</p>
        <p>Welcome aboard,<br>The KhmerFit Team</p>
    </div>
    <div class="footer"><p>KhmerFit · Phnom Penh, Cambodia</p></div>
</div>
</body>
</html>
