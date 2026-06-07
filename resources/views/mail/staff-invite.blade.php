<!DOCTYPE html>
<html>
<body style="font-family:sans-serif;color:#374151;padding:32px;">
<h2 style="color:#0d9488;">You've been added to {{ $staffRecord->gym->name }}</h2>
<p>Hi {{ $staffRecord->user->full_name }},</p>
<p>You have been added as <strong>{{ ucfirst($staffRecord->role) }}</strong> at <strong>{{ $staffRecord->gym->name }}</strong> on the OnePazz platform.</p>
@if($tempPassword)
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin:16px 0;">
    <p style="margin:0 0 8px;font-weight:600;">Your login credentials:</p>
    <p style="margin:0;">Email: <strong>{{ $staffRecord->user->email }}</strong></p>
    <p style="margin:0;">Password: <strong>{{ $tempPassword }}</strong></p>
    <p style="margin:8px 0 0;font-size:12px;color:#6b7280;">Please change your password after your first login.</p>
</div>
<p><a href="{{ url('/login') }}" style="background:#0d9488;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;display:inline-block;">Login to OnePazz</a></p>
@else
<p>You can now access the check-in screen at <a href="{{ url('/gym-portal/checkin-screen') }}">{{ url('/gym-portal/checkin-screen') }}</a>.</p>
@endif
<p style="color:#6b7280;font-size:12px;margin-top:24px;">OnePazz — Cambodia's Fitness Network</p>
</body>
</html>
