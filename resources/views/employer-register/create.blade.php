<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Company — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="shrink-0">
            <x-logo variant="dark" size="32" />
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">Sign in</a>
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

        v: {
            company_name:   '{{ old('company_name', $invitation?->company_name ?? '') }}',
            address_line1:  '{{ old('address_line1') }}',
            province:       '{{ old('province', 'Phnom Penh') }}',
            contact_name:   '{{ old('contact_name', $invitation?->contact_name ?? '') }}',
            contact_email:  '{{ old('contact_email', $invitation?->contact_email ?? '') }}',
            admin_name:     '{{ old('admin_name') }}',
            admin_email:    '{{ old('admin_email', $invitation?->contact_email ?? '') }}',
            password:       '',
            password_confirmation: '',
        },

        touched: {},

        touch(f) { this.touched[f] = true },

        touchAll(fields) { fields.forEach(f => this.touch(f)) },

        validEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e) },

        get errors() {
            const v = this.v;
            return {
                company_name:          !v.company_name.trim()           ? 'Company name is required' : '',
                address_line1:         !v.address_line1.trim()          ? 'Address is required' : '',
                province:              !v.province.trim()               ? 'Province is required' : '',
                contact_name:          !v.contact_name.trim()           ? 'Full name is required' : '',
                contact_email:         !v.contact_email.trim()          ? 'Email is required'
                                     : !this.validEmail(v.contact_email) ? 'Enter a valid email address' : '',
                plan_id:               !this.plan_id                    ? 'Please select a plan' : '',
                employee_count:        !this.employee_count             ? 'Number of employees is required'
                                     : this.employee_count < 1          ? 'Must be at least 1' : '',
                admin_name:            !v.admin_name.trim()             ? 'Full name is required' : '',
                admin_email:           !v.admin_email.trim()            ? 'Email is required'
                                     : !this.validEmail(v.admin_email)  ? 'Enter a valid email address' : '',
                password:              !v.password                      ? 'Password is required'
                                     : v.password.length < 8            ? 'Password must be at least 8 characters' : '',
                password_confirmation: !v.password_confirmation         ? 'Please confirm your password'
                                     : v.password !== v.password_confirmation ? 'Passwords do not match' : '',
            }
        },

        err(f) { return this.touched[f] ? this.errors[f] : '' },

        get step1Valid() {
            return !this.errors.company_name && !this.errors.address_line1 && !this.errors.province
                && !this.errors.contact_name && !this.errors.contact_email
        },
        get step2Valid() {
            return !this.errors.plan_id && !this.errors.employee_count
        },
        get step3Valid() {
            return !this.errors.admin_name && !this.errors.admin_email && !this.errors.password && !this.errors.password_confirmation
        },

        goStep(n) {
            if (n > this.step) {
                if (this.step === 1) {
                    this.touchAll(['company_name','address_line1','province','contact_name','contact_email'])
                    if (!this.step1Valid) return
                }
                if (this.step === 2) {
                    this.touchAll(['plan_id','employee_count'])
                    if (!this.step2Valid) return
                }
            }
            this.step = n
        },

        submitForm() {
            this.touchAll(['admin_name','admin_email','password','password_confirmation'])
            if (!this.step3Valid) return
            this.$refs.form.submit()
        },

        get selectedPlan() { return this.plans.find(p => p.id == this.plan_id) },
        get subtotal() {
            if (!this.selectedPlan || !this.employee_count) return 0;
            const m = this.billing_cycle === 'annual' ? 12 : this.billing_cycle === 'quarterly' ? 3 : 1;
            return (this.selectedPlan.price * this.employee_count * m).toFixed(2);
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
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition"
                    :class="step >= {{ $n }} ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400'">{{ $n }}</div>
                <span class="text-xs transition" :class="step >= {{ $n }} ? 'text-brand-600 font-medium' : 'text-gray-400'">{{ $label }}</span>
            </div>
            @if($n < 3)
            <div class="flex-1 h-px mx-2 mt-[-12px] transition-colors" :class="step > {{ $n }} ? 'bg-brand-500' : 'bg-gray-200'"></div>
            @endif
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-3">Step <span x-text="step"></span> of 3</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
        <ul class="list-disc list-inside space-y-1 text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('employer-register.store') }}" x-ref="form" @submit.prevent="submitForm">
        @csrf
        @if($invitation)<input type="hidden" name="invite_token" value="{{ $invitation->invite_token }}">@endif
        <input type="hidden" name="_step" :value="step">
        <input type="hidden" name="plan_id" x-model="plan_id">
        <input type="hidden" name="billing_cycle" x-model="billing_cycle">

        {{-- STEP 1 --}}
        <div x-show="step === 1">
            <div class="card p-6 mb-5">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Company Information</h2>
                <p class="text-sm text-gray-400 mb-5">Tell us about your organisation</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Company Name *</label>
                        <input type="text" name="company_name" x-model="v.company_name"
                            @blur="touch('company_name')"
                            :class="err('company_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('company_name')" x-text="err('company_name')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Company Name (Khmer)</label>
                        <input type="text" name="company_name_kh" value="{{ old('company_name_kh') }}" class="input">
                    </div>
                    <div>
                        <label class="label">Registration Number (MOC)</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="input" placeholder="Optional">
                    </div>
                    <div>
                        <label class="label">Industry</label>
                        <select name="industry" class="input">
                            <option value="">— Select —</option>
                            @foreach(['Retail','Technology','Finance','NGO','Healthcare','Education','Manufacturing','Hospitality','Other'] as $ind)
                            <option value="{{ $ind }}" {{ old('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Company Size</label>
                        <select name="company_size" class="input">
                            <option value="">— Select —</option>
                            @foreach(['1-10','11-50','51-100','101-250','250+'] as $sz)
                            <option value="{{ $sz }}" {{ old('company_size') === $sz ? 'selected' : '' }}>{{ $sz }} employees</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Address Line 1 *</label>
                        <input type="text" name="address_line1" x-model="v.address_line1"
                            @blur="touch('address_line1')"
                            :class="err('address_line1') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input" placeholder="Street address">
                        <p x-show="err('address_line1')" x-text="err('address_line1')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">City *</label>
                        <input type="text" name="city" value="{{ old('city', 'Phnom Penh') }}" class="input">
                    </div>
                    <div>
                        <label class="label">Province *</label>
                        <input type="text" name="province" x-model="v.province"
                            @blur="touch('province')"
                            :class="err('province') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input" placeholder="e.g. Phnom Penh">
                        <p x-show="err('province')" x-text="err('province')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                </div>
            </div>

            <div class="card p-6 mb-5">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Contact Person</h2>
                <p class="text-sm text-gray-400 mb-5">Primary point of contact for your account</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Full Name *</label>
                        <input type="text" name="contact_name" x-model="v.contact_name"
                            @blur="touch('contact_name')"
                            :class="err('contact_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('contact_name')" x-text="err('contact_name')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Work Email *</label>
                        <input type="email" name="contact_email" x-model="v.contact_email"
                            @blur="touch('contact_email')"
                            @input="touch('contact_email')"
                            :class="err('contact_email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('contact_email')" x-text="err('contact_email')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="input" placeholder="+855...">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="goStep(2)" class="btn-primary">Continue → Plan</button>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div x-show="step === 2" x-cloak>
            <div class="card p-6 mb-5">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Choose a Wellness Plan</h2>
                <p class="text-sm text-gray-400 mb-6">Priced per employee per month. Change plans anytime.</p>

                <div class="space-y-3 mb-6">
                    @foreach($plans as $plan)
                    <label class="flex items-start gap-4 p-4 border rounded-2xl cursor-pointer transition"
                        :class="plan_id == '{{ $plan->id }}' ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="_plan_display" value="{{ $plan->id }}"
                            x-model="plan_id" @change="touch('plan_id')" class="hidden">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-gray-800">{{ $plan->name }}</span>
                                <span class="text-brand-600 font-bold">${{ number_format($plan->price_usd, 2) }}<span class="text-xs text-gray-400 font-normal">/emp/mo</span></span>
                            </div>
                            @if($plan->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
                            @endif
                        </div>
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center mt-0.5 shrink-0 transition"
                            :class="plan_id == '{{ $plan->id }}' ? 'border-brand-600 bg-brand-600' : 'border-gray-300'">
                            <div class="w-2 h-2 rounded-full bg-white" x-show="plan_id == '{{ $plan->id }}'"></div>
                        </div>
                    </label>
                    @endforeach
                    <p x-show="err('plan_id')" x-text="err('plan_id')" class="text-xs text-red-500 mt-1"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <label class="label">Number of Employees *</label>
                        <input type="number" name="employee_count" x-model="employee_count"
                            @blur="touch('employee_count')"
                            @input="touch('employee_count')"
                            :class="err('employee_count') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            min="1" class="input font-mono" placeholder="e.g. 50">
                        <p x-show="err('employee_count')" x-text="err('employee_count')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Billing Cycle</label>
                        <select name="_billing_display" x-model="billing_cycle" class="input">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly (3 months)</option>
                            <option value="annual">Annual (12 months)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 bg-brand-50 border border-brand-100 rounded-xl p-4" x-show="plan_id && employee_count">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500" x-text="(selectedPlan?.name ?? '') + ' × ' + employee_count + ' employees × ' + cycleLabel"></span>
                        <span class="font-bold text-gray-900 font-mono" x-text="'$' + subtotal"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">An invoice will be generated. Payment by bank transfer — instructions after submission.</p>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" @click="goStep(1)" class="btn-secondary">← Back</button>
                <button type="button" @click="goStep(3)" class="btn-primary">Continue → Account</button>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div x-show="step === 3" x-cloak>
            <div class="card p-6 mb-5">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Create Your Admin Account</h2>
                <p class="text-sm text-gray-400 mb-5">You'll use this to manage employees and view reports after approval.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Full Name *</label>
                        <input type="text" name="admin_name" x-model="v.admin_name"
                            @blur="touch('admin_name')"
                            :class="err('admin_name') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('admin_name')" x-text="err('admin_name')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Login Email *</label>
                        <input type="email" name="admin_email" x-model="v.admin_email"
                            @blur="touch('admin_email')"
                            @input="touch('admin_email')"
                            :class="err('admin_email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('admin_email')" x-text="err('admin_email')" class="mt-1 text-xs text-red-500"></p>
                    </div>
                    <div>
                        <label class="label">Password *</label>
                        <input type="password" name="password" x-model="v.password"
                            @blur="touch('password')"
                            @input="touch('password')"
                            :class="err('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : ''"
                            class="input">
                        <p x-show="err('password')" x-text="err('password')" class="mt-1 text-xs text-red-500"></p>
                        {{-- Password strength bar --}}
                        <div class="mt-2 flex gap-1" x-show="v.password.length > 0">
                            <div class="h-1 flex-1 rounded-full transition-all"
                                :class="v.password.length >= 8 ? 'bg-brand-500' : 'bg-gray-200'"></div>
                            <div class="h-1 flex-1 rounded-full transition-all"
                                :class="v.password.length >= 10 && /[A-Z]/.test(v.password) ? 'bg-brand-500' : 'bg-gray-200'"></div>
                            <div class="h-1 flex-1 rounded-full transition-all"
                                :class="v.password.length >= 12 && /[0-9]/.test(v.password) && /[A-Z]/.test(v.password) ? 'bg-brand-500' : 'bg-gray-200'"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-400" x-show="v.password.length > 0 && !err('password')"
                            x-text="v.password.length >= 12 && /[0-9]/.test(v.password) && /[A-Z]/.test(v.password) ? 'Strong password' : v.password.length >= 10 && /[A-Z]/.test(v.password) ? 'Good — try adding numbers' : 'Minimum 8 characters'">
                        </p>
                    </div>
                    <div>
                        <label class="label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" x-model="v.password_confirmation"
                            @blur="touch('password_confirmation')"
                            @input="touch('password_confirmation')"
                            :class="err('password_confirmation') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : (v.password_confirmation && v.password === v.password_confirmation ? 'border-brand-400' : '')"
                            class="input">
                        <p x-show="err('password_confirmation')" x-text="err('password_confirmation')" class="mt-1 text-xs text-red-500"></p>
                        <p x-show="v.password_confirmation && v.password === v.password_confirmation && !err('password_confirmation')"
                           class="mt-1 text-xs text-brand-600">✓ Passwords match</p>
                    </div>
                </div>
            </div>

            <div class="bg-brand-50 border border-brand-200 rounded-2xl p-5 mb-5 space-y-2" x-show="plan_id && employee_count">
                <p class="text-sm font-bold text-brand-800 mb-2">Order Summary</p>
                <div class="flex justify-between text-sm text-gray-700">
                    <span x-text="selectedPlan?.name + ' Plan'"></span>
                    <span x-text="employee_count + ' employees'"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                    <span x-text="cycleLabel"></span>
                    <span class="font-bold font-mono" x-text="'$' + subtotal"></span>
                </div>
                <p class="text-xs text-brand-600 mt-2">Payment via bank transfer. Details after submitting.</p>
            </div>

            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 mb-6">
                <p class="text-xs text-gray-500">By submitting, you agree to our <a href="#" class="text-brand-600 underline">Terms of Service</a>. Your registration will be reviewed within 24 hours.</p>
            </div>

            <div class="flex justify-between">
                <button type="button" @click="goStep(2)" class="btn-secondary">← Back</button>
                <button type="submit" class="btn-primary">Submit Registration</button>
            </div>
        </div>

    </form>
</div>
</body>
</html>
