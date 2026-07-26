<x-layouts.admin title="Add New Employer">

<a href="{{ route('admin.employers.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Employers
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Add New Employer</h1>
    <p class="text-sm text-gray-400 mt-1">Manually create a corporate client account</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.employers.store') }}" x-data="{ createAccount: {{ old('create_account') ? 'true' : 'false' }}, hasPlan: {{ old('plan_id') ? 'true' : 'false' }} }">
    @csrf

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Company Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Company Name *</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" required class="input">
            </div>
            <div>
                <label class="label">Company Name (Khmer)</label>
                <input type="text" name="company_name_kh" value="{{ old('company_name_kh') }}" class="input">
            </div>
            <div>
                <label class="label">Registration Number (MOC)</label>
                <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="input">
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
                <label class="label">Status</label>
                <select name="status" required class="input">
                    @foreach(['active','pending','suspended'] as $s)
                    <option value="{{ $s }}" {{ old('status','active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="label">Address</label>
                <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="input">
            </div>
            <div>
                <label class="label">City</label>
                <input type="text" name="city" value="{{ old('city','Phnom Penh') }}" class="input">
            </div>
            <div>
                <label class="label">Province</label>
                <input type="text" name="province" value="{{ old('province','Phnom Penh') }}" class="input">
            </div>
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Contact Person</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Name *</label>
                <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="input">
            </div>
            <div>
                <label class="label">Email *</label>
                <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="input">
            </div>
            <div>
                <label class="label">Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="input">
            </div>
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Subscription <span class="text-gray-400 font-normal text-sm">(optional)</span></h3>
        <label class="flex items-center gap-2 cursor-pointer mb-4 text-sm text-gray-700">
            <input type="checkbox" x-model="hasPlan" class="rounded border-gray-300 accent-brand-500">
            Set up a subscription now
        </label>
        <div x-show="hasPlan" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Plan</label>
                <select name="plan_id" class="input">
                    <option value="">— Select plan —</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} — ${{ number_format($plan->price_usd,2) }}/employee/mo
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Employee Count</label>
                <input type="number" min="1" name="employee_count" value="{{ old('employee_count') }}" class="input font-mono">
            </div>
            <div>
                <label class="label">Billing Cycle</label>
                <select name="billing_cycle" class="input">
                    @foreach(['monthly'=>'Monthly','quarterly'=>'Quarterly (3 months)','annual'=>'Annual (12 months)'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('billing_cycle','monthly') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', today()->toDateString()) }}" class="input">
            </div>
        </div>
    </div>

    <div class="card p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Employer Admin Account</h3>
        <label class="flex items-center gap-2 cursor-pointer mb-4 text-sm text-gray-700">
            <input type="checkbox" name="create_account" value="1" x-model="createAccount" class="rounded border-gray-300 accent-brand-500">
            Create login account and send welcome email
        </label>
        <div x-show="createAccount" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Full Name</label>
                <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="input">
            </div>
            <div>
                <label class="label">Login Email</label>
                <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="input">
                <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Create Employer</button>
        <a href="{{ route('admin.employers.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

</x-layouts.admin>
