<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#374151;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:22px;}
.body{padding:32px;}
.body p{color:#374151;line-height:1.6;margin:0 0 16px;}
.reason{background:#fef9f0;border:1px solid #fde68a;border-radius:8px;padding:16px;margin:20px 0;}
.reason p{margin:0;color:#92400e;font-size:14px;}
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
        <p>Thank you for your interest in partnering with OnePazz. After reviewing your application for <strong>{{ $application->studio_name }}</strong>, we're unable to move forward at this time.</p>
        <div class="reason">
            <p><strong>Reason:</strong> {{ $reason }}</p>
        </div>
        <p>We appreciate the time you took to apply and encourage you to address the points above and reapply in the future. If you'd like to discuss this further, please reach out to us at <a href="mailto:partners@onepazz.com.kh" style="color:#0d9488;">partners@onepazz.com.kh</a>.</p>
        <p>Thank you again for your interest in OnePazz.</p>
        <p>Best regards,<br>The OnePazz Team</p>
    </div>
    <div class="footer">
        <p>OnePazz · Phnom Penh, Cambodia</p>
    </div>
</div>
</body>
</html>
