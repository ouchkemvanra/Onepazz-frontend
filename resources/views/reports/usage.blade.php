<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>KhmerFit Usage Report — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1f2937; background: #fff; }

    .header { background: #0f766e; color: white; padding: 18px 24px; margin-bottom: 16px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .brand { font-size: 18px; font-weight: 700; letter-spacing: -0.3px; }
    .brand-sub { font-size: 10px; color: rgba(255,255,255,0.7); margin-top: 2px; }
    .report-title { text-align: right; }
    .report-title h2 { font-size: 14px; font-weight: 600; }
    .report-title p { font-size: 10px; color: rgba(255,255,255,0.75); margin-top: 3px; }

    .body { padding: 0 24px 24px; }

    .summary { display: flex; gap: 12px; margin-bottom: 18px; }
    .stat-box { flex: 1; background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 6px; padding: 10px 14px; }
    .stat-label { font-size: 9px; font-weight: 600; color: #0f766e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .stat-value { font-size: 22px; font-weight: 700; color: #0f766e; }
    .stat-sub { font-size: 9px; color: #6b7280; margin-top: 2px; }

    .section-title { font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }

    table { width: 100%; border-collapse: collapse; font-size: 9px; }
    thead tr { background: #0f766e; color: white; }
    thead th { padding: 7px 8px; text-align: left; font-weight: 600; font-size: 8.5px; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody tr:nth-child(odd)  { background: #ffffff; }
    tbody td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }

    .card-no { font-family: DejaVu Sans Mono, monospace; color: #0f766e; font-size: 8.5px; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 99px; font-size: 8px; font-weight: 600; }
    .badge-dept { background: #eff6ff; color: #1d4ed8; }
    .empty { text-align: center; padding: 32px; color: #9ca3af; font-style: italic; }

    .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; color: #9ca3af; font-size: 8px; }

    @page { margin: 15mm 12mm; }
</style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="brand">🏃 KhmerFit</div>
            <div class="brand-sub">Corporate Wellness Platform · Phnom Penh, Cambodia</div>
        </div>
        <div class="report-title">
            <h2>Usage Report</h2>
            <p>{{ $employer->company_name }}</p>
            <p>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
        </div>
    </div>
</div>

<div class="body">

    {{-- ── SUMMARY STATS ── --}}
    @php
        $totalCheckins   = $checkins->count();
        $uniqueEmployees = $checkins->pluck('employee_id')->unique()->count();
        $uniqueGyms      = $checkins->pluck('gym_id')->unique()->count();
        $avgDuration     = $checkins->whereNotNull('duration_minutes')->avg('duration_minutes');
        $monthLabel      = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y');
        $monthlyCost     = $sub ? ($sub->employee_count * ($sub->plan->price_usd ?? 0)) : 0;
    @endphp

    <div class="summary">
        <div class="stat-box">
            <div class="stat-label">Total Check-ins</div>
            <div class="stat-value">{{ $totalCheckins }}</div>
            <div class="stat-sub">{{ $monthLabel }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Active Members</div>
            <div class="stat-value">{{ $uniqueEmployees }}</div>
            <div class="stat-sub">of {{ $sub?->employee_count ?? '—' }} enrolled</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Gyms Visited</div>
            <div class="stat-value">{{ $uniqueGyms }}</div>
            <div class="stat-sub">unique locations</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Avg. Duration</div>
            <div class="stat-value">{{ $avgDuration ? round($avgDuration) : '—' }}</div>
            <div class="stat-sub">minutes per visit</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Monthly Cost</div>
            <div class="stat-value" style="font-size:16px;">${{ number_format($monthlyCost, 0) }}</div>
            <div class="stat-sub">≈ {{ number_format($monthlyCost * $khrRate, 0) }} ៛</div>
        </div>
    </div>

    {{-- ── CHECKIN TABLE ── --}}
    <div class="section-title">Employee Check-in Detail</div>

    @if($checkins->isEmpty())
        <div class="empty">No check-ins recorded for {{ $monthLabel }}.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Membership Card</th>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Gym</th>
                    <th>City</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration (min)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checkins as $c)
                <tr>
                    <td><span class="card-no">{{ $c->employee?->membership_card_no ?? '—' }}</span></td>
                    <td style="font-weight:600;">{{ $c->user?->full_name ?? '—' }}</td>
                    <td style="color:#6b7280;">{{ $c->user?->email ?? '—' }}</td>
                    <td>
                        @if($c->employee?->department)
                            <span class="badge badge-dept">{{ $c->employee->department }}</span>
                        @else
                            <span style="color:#d1d5db;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:500;">{{ $c->gym?->name ?? '—' }}</td>
                    <td style="color:#6b7280;">{{ $c->gym?->city ?? '—' }}</td>
                    <td style="white-space:nowrap;">{{ $c->checked_in_at->format('d M Y') }}</td>
                    <td style="font-family: DejaVu Sans Mono, monospace; color:#0f766e;">{{ $c->checked_in_at->format('H:i') }}</td>
                    <td style="text-align:center;">{{ $c->duration_minutes ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <span>{{ $employer->company_name }} · Generated {{ now()->format('d M Y, H:i') }}</span>
        <span>KhmerFit Co., Ltd · Phnom Penh, Cambodia · khmerfit.com.kh</span>
    </div>

</div>
</body>
</html>
