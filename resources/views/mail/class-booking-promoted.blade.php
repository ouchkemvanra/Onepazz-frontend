<!DOCTYPE html>
<html>
<body style="font-family:sans-serif;color:#374151;padding:32px;">
<h2 style="color:#0d9488;">You're off the waitlist!</h2>
<p>Hi {{ $booking->user->full_name }},</p>
<p>A spot has opened up and your booking for <strong>{{ $booking->gymClass->name }}</strong> at <strong>{{ $booking->gymClass->gym->name }}</strong> is now <strong>confirmed</strong>.</p>
<p>Class time: {{ \Carbon\Carbon::parse($booking->gymClass->start_time)->format('g:i A') }}</p>
<p>See you there!</p>
<p style="color:#6b7280;font-size:12px;">KhmerFit — Cambodia's Fitness Network</p>
</body>
</html>
