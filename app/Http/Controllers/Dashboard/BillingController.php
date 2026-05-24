<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSubmittedMail;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PlatformConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BillingController extends Controller
{
    private function bankDetails(): array
    {
        return [
            'bank'    => PlatformConfig::get('bank_name', 'ACLEDA Bank'),
            'account' => PlatformConfig::get('bank_account', '1234-5678-9012-3456'),
            'holder'  => PlatformConfig::get('bank_holder', 'KhmerFit Co., Ltd'),
        ];
    }

    public function index()
    {
        $employer = auth()->user()->adminForEmployer;

        if (!$employer) {
            abort(403);
        }

        $invoices = $employer->invoices()->latest()->paginate(15);

        return view('dashboard.billing.index', [
            'employer'    => $employer,
            'invoices'    => $invoices,
            'bankDetails' => $this->bankDetails(),
        ]);
    }

    public function showPayForm(Invoice $invoice)
    {
        $employer = auth()->user()->adminForEmployer;

        if (!$employer || $invoice->employer_id !== $employer->id) {
            abort(403);
        }

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return redirect()->route('dashboard.billing.index')
                ->with('error', 'This invoice cannot accept a new payment.');
        }

        return view('dashboard.billing.pay', [
            'invoice'     => $invoice,
            'bankDetails' => $this->bankDetails(),
        ]);
    }

    public function submitPayment(Request $request, Invoice $invoice)
    {
        $employer = auth()->user()->adminForEmployer;

        if (!$employer || $invoice->employer_id !== $employer->id) {
            abort(403);
        }

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return redirect()->route('dashboard.billing.index')
                ->with('error', 'This invoice cannot accept a new payment.');
        }

        $request->validate([
            'transfer_reference' => 'required|string|max:100',
            'transfer_date'      => 'required|date',
            'bank_name'          => 'nullable|string|max:100',
            'receipt'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')
                ->store("receipts/{$invoice->employer_id}/{$invoice->id}", 'public');
        }

        Payment::create([
            'invoice_id'         => $invoice->id,
            'employer_id'        => $invoice->employer_id,
            'amount_usd'         => $invoice->total_usd,
            'amount_khr'         => $invoice->total_khr,
            'transfer_reference' => $request->transfer_reference,
            'transfer_date'      => $request->transfer_date,
            'bank_name'          => $request->bank_name,
            'receipt_path'       => $receiptPath,
            'status'             => 'pending',
        ]);

        $invoice->update(['status' => 'pending_verification']);

        // Notify all platform admins
        $admins = User::where('role', 'platform_admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new PaymentSubmittedMail($invoice, $employer));
        }

        return redirect()->route('dashboard.billing.index')
            ->with('success', 'Payment submitted successfully. We will confirm within 1–2 business days.');
    }
}
