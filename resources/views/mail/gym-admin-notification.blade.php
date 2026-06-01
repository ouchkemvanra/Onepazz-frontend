<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:'DM Sans',Arial,sans-serif;background:#f9fafb;margin:0;padding:0;}
.wrap{max-width:560px;margin:40px auto;background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;}
.header{background:#1f2937;padding:24px 32px;}
.header h1{color:#fff;margin:0;font-size:18px;}
.body{padding:32px;}
.body p{color:#374151;line-height:1.6;margin:0 0 12px;}
.row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;font-size:14px;}
.row span:first-child{color:#9ca3af;}
.row span:last-child{color:#111827;font-weight:500;}
.btn-wrap{text-align:center;margin:24px 0 0;}
.btn{display:inline-block;background:#0d9488;color:#fff;text-decoration:none;padding:10px 24px;border-radius:8px;font-weight:600;font-size:14px;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>
            @if($event === 'accepted')
                ✅ Invitation Accepted
            @else
                📋 New Gym Application
            @endif
        </h1>
    </div>
    <div class="body">
        @if($event === 'accepted')
            <p><strong>{{ $application->studio_name }}</strong> has accepted your invitation and submitted their profile. It's now under review.</p>
        @else
            <p>A new gym partner application has been submitted and is waiting for review.</p>
        @endif

        <div style="margin:16px 0;">
            <div class="row"><span>Studio</span><span>{{ $application->studio_name }}</span></div>
            <div class="row"><span>Contact</span><span>{{ $application->contact_name }}</span></div>
            <div class="row"><span>Email</span><span>{{ $application->contact_email }}</span></div>
            <div class="row"><span>City</span><span>{{ $application->city }}</span></div>
            <div class="row"><span>Submitted</span><span>{{ $application->created_at->format('d M Y H:i') }}</span></div>
        </div>

        <div class="btn-wrap">
            <a href="{{ url('/admin/gym-applications/' . $application->id) }}" class="btn">Review Application →</a>
        </div>
    </div>
</div>
</body>
</html>
