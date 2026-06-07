<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#374151;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:20px;}
.body{padding:32px;}
p{color:#374151;line-height:1.6;margin:0 0 14px;font-size:14px;}
.reason{background:#fef9f0;border:1px solid #fde68a;border-radius:8px;padding:16px;margin:16px 0;}
.reason p{margin:0;color:#92400e;}
.footer{padding:20px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header"><h1>🏃 OnePazz — Update on Your Registration</h1></div>
    <div class="body">
        <p>Hi {{ $employer->contact_name }},</p>
        <p>Thank you for your interest in OnePazz. After reviewing your registration for <strong>{{ $employer->company_name }}</strong>, we're unable to proceed at this time.</p>
        <div class="reason"><p><strong>Reason:</strong> {{ $reason }}</p></div>
        <p>We appreciate your interest and encourage you to reach out if you'd like to discuss further or reapply in the future.</p>
        <p>Contact us at <a href="mailto:support@onepazz.com.kh" style="color:#0d9488;">support@onepazz.com.kh</a>.</p>
        <p>The OnePazz Team</p>
    </div>
    <div class="footer"><p>OnePazz · Phnom Penh, Cambodia</p></div>
</div>
</body>
</html>
