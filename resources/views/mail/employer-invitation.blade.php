<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:linear-gradient(135deg,#1e3a5f,#0d9488);padding:40px 32px;text-align:center;}
.header h1{color:#fff;margin:0 0 6px;font-size:22px;}
.header p{color:#99f6e4;margin:0;font-size:13px;}
.body{padding:32px;}
p{color:#374151;line-height:1.6;margin:0 0 14px;font-size:14px;}
.msg{background:#f0fdfa;border-left:4px solid #0d9488;border-radius:0 8px 8px 0;padding:14px;margin:16px 0;}
.msg p{margin:0;font-style:italic;}
.plan{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin:16px 0;text-align:center;}
.plan p{margin:0;font-size:13px;color:#374151;}
.btn-wrap{text-align:center;margin:24px 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:13px 32px;border-radius:8px;font-weight:600;font-size:15px;}
.expiry{background:#fef9f0;border:1px solid #fde68a;border-radius:8px;padding:10px;text-align:center;}
.expiry p{margin:0;color:#92400e;font-size:12px;}
.footer{padding:20px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🏃 You're Invited to OnePazz</h1>
        <p>Corporate Wellness for Cambodia's Leading Companies</p>
    </div>
    <div class="body">
        <p>Hi {{ $invitation->contact_name }},</p>
        <p>The OnePazz team would like to invite <strong>{{ $invitation->company_name ?? 'your company' }}</strong> to join our corporate wellness platform — connecting your employees with Cambodia's top gyms and fitness studios.</p>

        @if($invitation->personal_message)
        <div class="msg"><p>{{ $invitation->personal_message }}</p></div>
        @endif

        @if($invitation->suggestedPlan)
        <div class="plan">
            <p>Suggested plan: <strong>{{ $invitation->suggestedPlan->name }}</strong> — ${{ number_format($invitation->suggestedPlan->price_usd, 2) }} / employee / month</p>
        </div>
        @endif

        <div class="btn-wrap">
            <a href="{{ url('/register/employer/accept/' . $invitation->invite_token) }}" class="btn">Accept Invitation & Register →</a>
        </div>

        <div class="expiry">
            <p>⏰ This invitation expires on <strong>{{ $invitation->invite_expires_at->format('d M Y') }}</strong></p>
        </div>
        <br>
        <p style="font-size:12px;color:#6b7280;">Questions? Email us at <a href="mailto:partners@onepazz.com.kh" style="color:#0d9488;">partners@onepazz.com.kh</a></p>
    </div>
    <div class="footer"><p>OnePazz · Phnom Penh, Cambodia</p></div>
</div>
</body>
</html>
