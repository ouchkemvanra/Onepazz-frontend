<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#0d9488;padding:32px;text-align:center;}
.header h1{color:#fff;margin:0;font-size:20px;}
.body{padding:32px;}
p{color:#374151;line-height:1.6;margin:0 0 14px;font-size:14px;}
.ref{background:#f0fdfa;border:2px solid #0d9488;border-radius:10px;padding:20px;text-align:center;margin:20px 0;}
.ref .label{font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;}
.ref .code{font-size:28px;font-weight:700;color:#0d9488;font-family:monospace;letter-spacing:.1em;}
.bank{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin:20px 0;}
.bank-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f1f5f9;font-size:13px;}
.bank-row:last-child{border:none;}
.bank-row span:first-child{color:#6b7280;}
.bank-row span:last-child{font-weight:600;color:#111827;}
.amount{background:#fef9f0;border:1px solid #fde68a;border-radius:8px;padding:14px;text-align:center;margin:16px 0;}
.amount p{margin:0;color:#92400e;font-size:15px;}
.amount strong{font-size:20px;}
.footer{padding:20px 32px;border-top:1px solid #f3f4f6;text-align:center;}
.footer p{color:#9ca3af;font-size:12px;margin:0;}
</style></head>
<body>
<div class="wrap">
    <div class="header"><h1>🏃 OnePazz — Registration Received</h1></div>
    <div class="body">
        <p>Hi {{ $employer->contact_name }},</p>
        <p>Thank you for registering <strong>{{ $employer->company_name }}</strong> on OnePazz. To activate your account, please complete the bank transfer below.</p>

        <div class="ref">
            <div class="label">Your Payment Reference Code</div>
            <div class="code">{{ $employer->reference_code }}</div>
            <div style="font-size:12px;color:#6b7280;margin-top:6px;">Include this code in your transfer description</div>
        </div>

        <div class="amount">
            <p>Amount Due: <strong>${{ number_format($invoice->total_usd, 2) }} USD</strong>
            ({{ number_format($invoice->total_khr) }} ៛)</p>
        </div>

        <p><strong>Bank Transfer Details:</strong></p>
        <div class="bank">
            <div class="bank-row"><span>Bank</span><span>{{ $bankDetails['bank'] }}</span></div>
            <div class="bank-row"><span>Account Number</span><span>{{ $bankDetails['account'] }}</span></div>
            <div class="bank-row"><span>Account Holder</span><span>{{ $bankDetails['holder'] }}</span></div>
            <div class="bank-row"><span>Reference</span><span>{{ $employer->reference_code }}</span></div>
            <div class="bank-row"><span>Amount</span><span>${{ number_format($invoice->total_usd, 2) }}</span></div>
        </div>

        <p>Once your payment is received and verified, your account will be activated within <strong>1–2 business days</strong>. You'll receive a confirmation email as soon as it's ready.</p>
        <p>If you have any questions, contact us at <a href="mailto:billing@onepazz.com.kh" style="color:#0d9488;">billing@onepazz.com.kh</a>.</p>
        <p>The OnePazz Team</p>
    </div>
    <div class="footer"><p>OnePazz · Phnom Penh, Cambodia</p></div>
</div>
</body>
</html>
