<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#0d9488;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:22px;}
.body{padding:32px;}
.body p{color:#374151;line-height:1.6;margin:0 0 16px;}
.highlight{background:#f0fdfa;border:1px solid #99f6e4;border-radius:8px;padding:16px;margin:20px 0;}
.highlight p{margin:0;color:#0f766e;font-size:14px;}
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
        <p>Thank you for applying to become a OnePazz partner! We've received your application for <strong>{{ $application->studio_name }}</strong>.</p>
        <div class="highlight">
            <p>⏱ Our team will review your application within <strong>3–5 business days</strong>. We'll contact you at this email address with our decision.</p>
        </div>
        <p>In the meantime, if you have any questions feel free to reach out to us at <a href="mailto:partners@onepazz.com.kh" style="color:#0d9488;">partners@onepazz.com.kh</a>.</p>
        <p>Best regards,<br>The OnePazz Team</p>
    </div>
    <div class="footer">
        <p>OnePazz · Phnom Penh, Cambodia</p>
    </div>
</div>
</body>
</html>
