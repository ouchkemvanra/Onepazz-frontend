<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employer;
use App\Models\Invoice;
use App\Models\Payment;

class InvoiceAndPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $smartRetail = Employer::where('contact_email', 'sokha@smartretail.com.kh')->first();
        $sub         = $smartRetail->activeSubscription;
        $khrRate     = 4100;

        $mayInvoice = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-05-0001'],
            [
                'employer_id'          => $smartRetail->id,
                'subscription_id'      => $sub->id,
                'billing_period_start' => '2026-05-01',
                'billing_period_end'   => '2026-05-31',
                'employee_count'       => 120,
                'plan_price_usd'       => 45.00,
                'subtotal_usd'         => 5400.00,
                'total_usd'            => 5400.00,
                'khr_rate'             => $khrRate,
                'total_khr'            => 5400 * $khrRate,
                'status'               => 'pending_verification',
                'due_date'             => '2026-05-10',
            ]
        );

        Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-04-0001'],
            [
                'employer_id'          => $smartRetail->id,
                'subscription_id'      => $sub->id,
                'billing_period_start' => '2026-04-01',
                'billing_period_end'   => '2026-04-30',
                'employee_count'       => 120,
                'plan_price_usd'       => 45.00,
                'subtotal_usd'         => 5400.00,
                'total_usd'            => 5400.00,
                'khr_rate'             => $khrRate,
                'total_khr'            => 5400 * $khrRate,
                'status'               => 'paid',
                'due_date'             => '2026-04-10',
            ]
        );

        Payment::updateOrCreate(
            ['invoice_id' => $mayInvoice->id],
            [
                'employer_id'        => $smartRetail->id,
                'amount_usd'         => 5400.00,
                'amount_khr'         => 5400 * $khrRate,
                'transfer_reference' => 'SRS-INV-2026-05-0001',
                'transfer_date'      => '2026-05-10',
                'bank_name'          => 'ACLEDA Bank',
                'status'             => 'pending',
            ]
        );
    }
}
