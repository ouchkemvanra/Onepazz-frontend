<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\PlatformConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function resolveMonth(Request $request): array
    {
        $month = $request->month ?? now()->format('Y-m');

        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        [$year, $mon] = explode('-', $month);

        return [$month, (int) $year, (int) $mon];
    }

    private function getCheckins(int $employerId, int $year, int $mon)
    {
        return Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employerId))
            ->whereYear('checked_in_at', $year)
            ->whereMonth('checked_in_at', $mon)
            ->with([
                'user:id,full_name,email',
                'gym:id,name,city',
                'employee:id,department,membership_card_no',
            ])
            ->orderByDesc('checked_in_at')
            ->get();
    }

    public function downloadCsv(Request $request)
    {
        $employer = auth()->user()->adminForEmployer;

        if (!$employer) {
            abort(403);
        }

        [$month, $year, $mon] = $this->resolveMonth($request);
        $checkins = $this->getCheckins($employer->id, $year, $mon);

        $filename = "onepazz-report-{$month}.csv";

        $callback = function () use ($checkins) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM — ensures Excel renders Khmer characters correctly
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Membership Card', 'Employee Name', 'Email',
                'Department', 'Gym', 'City',
                'Date', 'Time', 'Duration (min)',
            ]);

            foreach ($checkins as $c) {
                fputcsv($handle, [
                    $c->employee?->membership_card_no,
                    $c->user?->full_name,
                    $c->user?->email,
                    $c->employee?->department ?? '',
                    $c->gym?->name,
                    $c->gym?->city,
                    $c->checked_in_at->format('d/m/Y'),
                    $c->checked_in_at->format('H:i'),
                    $c->duration_minutes ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $employer = auth()->user()->adminForEmployer;

        if (!$employer) {
            abort(403);
        }

        [$month, $year, $mon] = $this->resolveMonth($request);
        $checkins = $this->getCheckins($employer->id, $year, $mon);

        $khrRate = (float) PlatformConfig::get('khr_rate', 4100);
        $sub     = $employer->activeSubscription()->with('plan')->first();

        $pdf = Pdf::loadView('reports.usage', compact(
            'employer', 'checkins', 'month', 'khrRate', 'sub'
        ))->setPaper('A4', 'landscape');

        return $pdf->download("onepazz-report-{$month}.pdf");
    }
}
