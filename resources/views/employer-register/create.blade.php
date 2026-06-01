<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Company — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">

{{-- Nav --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">Sign in</a>
            <a href="#plans" class="text-sm text-gray-500 hover:text-gray-800">Plans</a>
        </div>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-12"
    x-data="{
        step: {{ old('_step', 1) }},
        plan_id: '{{ old('plan_id', $invitation?->suggested_plan_id ?? '') }}',
        billing_cycle: '{{ old('billing_cycle', 'monthly') }}',
        employee_count: '{{ old('employee_count', '') }}',
        plans: {{ $plans->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'price'=>$p->price_usd])->toJson() }},
        get selectedPlan() { return this.plans.find(p => p.id == this.plan_id); },
        get subtotal() {
            if (!this.selectedPlan || !this.employee_count) return 0;
            const multiplier = this.billing_cycle === 'annual' ? 12 : this.billing_cycle === 'quarterly' ? 3 : 1;
            return (this.selectedPlan.price * this.employee_count * multiplier).toFixed(2);
        },
        get cycleLabel() {
            return this.billing_cycle === 'annual' ? '12 months' : this.billing_cycle === 'quarterly' ? '3 months' : '1 month';
        }
    }">

    {{-- Step Progress --}}
    <div class="mb-10">
        <div class="flex items-center justify-between max-w-xs mx-auto">
            @foreach([1 => 'Company', 2 => 'Plan', 3 => 'Account'] as $n => $label)
            <div class="flex flex-col items-center gap-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                    {{ 'step >= '.$n }} ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-400' "
                    :class="step >= {{ $n }} ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-400'">{{ $n }}</div>
                <span class="text-xs" :class="step >= {{ $n }} ? 'text-teal-600 font-medium' : 'text-gray-400'">{{ $label }}</span>
            </div>
            @if($n < 3)
            <div class="flex-1 h-px mx-2 mt-[-12px]" :class="step > {{ $n }} ? 'bg-teal-600' : 'bg-gray-200'"></div>
            @endif
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-3">Step <span x-text="step"></span> of 3</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('employer-register.store') }}" x-ref="form">
        @csrf
        @if($invitation)
        <input type="hidden" name="invite_token" value="{{ $invitation->invite_token }}">
        @endif
        <input type="hidden" name="_step" :value="step">
        <input type="hidden" name="plan_id" x-model="plan_id">
        <input type="hidden" name="billing_cycle" x-model="billing_cycle">

        {{-- STEP 1: Company Info --}}
        <div x-show="step === 1">
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Company Information</h2>
                <p class="text-sm text-gray-400 mb-5">Tell us about your organisation</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $invitation?->company_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name (Khmer)</label>
                        <input type="text" name="company_name_kh" value="{{ old('company_name_kh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number (MOC)</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                        <select name="industry" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                            <option value="">— Select —</option>
                            @foreach(['Retail','Technology','Finance','NGO','Healthcare','Education','Manufacturing','Hospitality','Other'] as $ind)
                            <option value="{{ $ind }}" {{ old('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                        <select name="company_size" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                            <option value="">— Select —</option>
                            @foreach(['1-10','11-50','51-100','101-250','250+'] as $sz)
                            <option value="{{ $sz }}" {{ old('company_size') === $sz ? 'selected' : '' }}>{{ $sz }} employees</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', 'Phnom Penh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Contact Person</h2>
                <p class="text-sm text-gray-400 mb-5">Primary point of contact for your account</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_name" value="{{ old('contact_name', $invitation?->contact_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Work Email <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $invitation?->contact_email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500" placeholder="+855...">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="step = 2" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">
                    Continue → Plan
                </button>
            </div>
        </div>

        {{-- STEP 2: Plan Selection --}}
        <div x-show="step === 2" x-cloak>
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Choose a Wellness Plan</h2>
                <p class="text-sm text-gray-400 mb-6">Priced per employee per month. Change plans anytime.</p>

                <div class="space-y-3 mb-6">
                    @foreach($plans as $plan)
                    <label class="flex items-start gap-4 p-4 border rounded-xl cursor-pointer transition"
                        :class="plan_id == '{{ $plan->id }}' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="_plan_display" value="{{ $plan->id }}" x-model="plan_id" class="mt-1 text-teal-600 hidden">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-gray-800">{{ $plan->name }}</span>
                                <span class="text-teal-600 font-bold">${{ number_format($plan->price_usd, 2) }}<span class="text-xs text-gray-400 font-normal">/employee/mo</span></span>
                            </div>
                            @if($plan->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
                            @endif
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center mt-0.5 shrink-0"
                            :class="plan_id == '{{ $plan->id }}' ? 'border-teal-600 bg-teal-600' : 'border-gray-300'">
                            <div class="w-2 h-2 rounded-full bg-white" x-show="plan_id == '{{ $plan->id }}'"></div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Number of Employees <span class="text-red-500">*</span></label>
                        <input type="number" name="employee_count" x-model="employee_count" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:border-teal-500" placeholder="e.g. 50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select name="_billing_display" x-model="billing_cycle" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly (3 months)</option>
                            <option value="annual">Annual (12 months)</option>
                        </select>
                    </div>
                </div>

                {{-- Invoice Preview --}}
                <div class="mt-5 bg-gray-50 rounded-lg p-4" x-show="plan_id && employee_count">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500" x-text="(selectedPlan?.name ?? '') + ' × ' + employee_count + ' employees × ' + cycleLabel"></span>
                        <span class="font-bold text-gray-800 text-base font-mono" x-text="'$' + subtotal"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">An invoice will be generated for this amount. Payment is by bank transfer — instructions on the next page.</p>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" @click="step = 1" class="border border-gray-200 px-6 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50">← Back</button>
                <button type="button" @click="step = 3" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">Continue → Account</button>
            </div>
        </div>

        {{-- STEP 3: Admin Account --}}
        <div x-show="step === 3" x-cloak>
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">Create Your Admin Account</h2>
                <p class="text-sm text-gray-400 mb-5">You'll use this to manage employees and view reports after approval.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Login Email <span class="text-red-500">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email', $invitation?->contact_email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 mb-6 space-y-2" x-show="plan_id && employee_count">
                <p class="text-sm font-semibold text-teal-800 mb-2">Order Summary</p>
                <div class="flex justify-between text-sm text-gray-700">
                    <span x-text="selectedPlan?.name + ' Plan'"></span>
                    <span x-text="employee_count + ' employees'"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                    <span x-text="cycleLabel"></span>
                    <span class="font-bold font-mono" x-text="'$' + subtotal"></span>
                </div>
                <p class="text-xs text-teal-600 mt-2">Payment via bank transfer. You'll receive details on the next page after submitting.</p>
            </div>

            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
                <p class="text-xs text-gray-500">By submitting, you agree to our <a href="#" class="text-teal-600 underline">Terms of Service</a>. Your registration will be reviewed by our team within 24 hours. You'll receive a confirmation email with bank transfer details.</p>
            </div>

            <div class="flex justify-between">
                <button type="button" @click="step = 2" class="border border-gray-200 px-6 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50">← Back</button>
                <button type="submit" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">Submit Registration</button>
            </div>
        </div>

    </form>
</div>
</body>
</html>
