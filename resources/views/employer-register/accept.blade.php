<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation — OnePazz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            OnePazz
        </a>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-12">

    {{-- Invitation Banner --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 mb-8 flex items-start gap-4">
        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center text-white shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-teal-800">You've been invited to join OnePazz</p>
            <p class="text-sm text-teal-600 mt-0.5">
                Hi {{ $invitation->contact_name }}, complete the form below to set up your company account.
                @if($invitation->suggestedPlan)
                A <strong>{{ $invitation->suggestedPlan->name }}</strong> plan has been suggested for you.
                @endif
            </p>
            @if($invitation->personal_message)
            <p class="text-sm text-teal-700 mt-2 italic">"{{ $invitation->personal_message }}"</p>
            @endif
            <p class="text-xs text-teal-500 mt-2">This invitation expires {{ $invitation->invite_expires_at->format('d M Y') }}.</p>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Complete Your Registration</h1>
    <p class="text-sm text-gray-400 mb-8">Your details have been pre-filled from the invitation. Review and complete all sections.</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('employer-register.submit', $invitation->invite_token) }}">
        @csrf

        {{-- Company --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Company Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $invitation->company_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
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

        {{-- Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Contact Person</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $invitation->contact_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Work Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $invitation->contact_email) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500" placeholder="+855...">
                </div>
            </div>
        </div>

        {{-- Plan --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6" x-data="{ plan_id: '{{ old('plan_id', $invitation->suggested_plan_id ?? '') }}', billing_cycle: '{{ old('billing_cycle', 'monthly') }}', employee_count: '{{ old('employee_count') }}', plans: {{ $plans->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'price'=>$p->price_usd])->toJson() }}, get selectedPlan() { return this.plans.find(p => p.id == this.plan_id); }, get subtotal() { if (!this.selectedPlan || !this.employee_count) return 0; const m = this.billing_cycle === 'annual' ? 12 : this.billing_cycle === 'quarterly' ? 3 : 1; return (this.selectedPlan.price * this.employee_count * m).toFixed(2); } }">
            <h3 class="font-semibold mb-4">Wellness Plan</h3>
            <input type="hidden" name="plan_id" x-model="plan_id">
            <input type="hidden" name="billing_cycle" x-model="billing_cycle">
            <div class="space-y-2 mb-4">
                @foreach($plans as $plan)
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer"
                    :class="plan_id == '{{ $plan->id }}' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" value="{{ $plan->id }}" x-model="plan_id" class="hidden">
                    <div class="flex-1 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800">{{ $plan->name }}</span>
                        <span class="text-sm text-teal-600 font-bold">${{ number_format($plan->price_usd, 2) }}<span class="text-xs text-gray-400 font-normal">/emp/mo</span></span>
                    </div>
                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                        :class="plan_id == '{{ $plan->id }}' ? 'border-teal-600 bg-teal-600' : 'border-gray-300'">
                        <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="plan_id == '{{ $plan->id }}'"></div>
                    </div>
                </label>
                @endforeach
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employees <span class="text-red-500">*</span></label>
                    <input type="number" name="employee_count" x-model="employee_count" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:border-teal-500">
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
            <div class="mt-3 bg-teal-50 rounded-lg px-4 py-3 flex justify-between text-sm" x-show="plan_id && employee_count">
                <span class="text-gray-500" x-text="selectedPlan?.name + ' × ' + employee_count + ' employees'"></span>
                <span class="font-bold font-mono text-teal-700" x-text="'$' + subtotal"></span>
            </div>
        </div>

        {{-- Admin Account --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Create Your Account</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="admin_name" value="{{ old('admin_name', $invitation->contact_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Email <span class="text-red-500">*</span></label>
                    <input type="email" name="admin_email" value="{{ old('admin_email', $invitation->contact_email) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl hover:bg-teal-700 font-medium">Submit Registration</button>
    </form>
</div>
</body>
</html>
