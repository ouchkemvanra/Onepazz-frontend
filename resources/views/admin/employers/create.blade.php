<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employer — Admin — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">
@include('admin._nav')

<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-6"><a href="{{ route('admin.employers.index') }}" class="text-sm text-teal-600 hover:text-teal-700">← Back to Employers</a></div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Add New Employer</h1>
    <p class="text-sm text-gray-400 mb-8">Manually create a corporate client account</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.employers.store') }}" x-data="{ createAccount: {{ old('create_account') ? 'true' : 'false' }}, hasPlan: {{ old('plan_id') ? 'true' : 'false' }} }">
        @csrf

        {{-- Company Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Company Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name (Khmer)</label>
                    <input type="text" name="company_name_kh" value="{{ old('company_name_kh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number (MOC)</label>
                    <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                    <select name="industry" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select —</option>
                        @foreach(['Retail','Technology','Finance','NGO','Healthcare','Education','Manufacturing','Hospitality','Other'] as $ind)
                        <option value="{{ $ind }}" {{ old('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                    <select name="company_size" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select —</option>
                        @foreach(['1-10','11-50','51-100','101-250','250+'] as $sz)
                        <option value="{{ $sz }}" {{ old('company_size') === $sz ? 'selected' : '' }}>{{ $sz }} employees</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['active','pending','suspended'] as $s)
                        <option value="{{ $s }}" {{ old('status','active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city','Phnom Penh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="province" value="{{ old('province','Phnom Penh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Contact Person</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        {{-- Plan --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Subscription (optional)</h3>
            <label class="flex items-center gap-3 cursor-pointer mb-4">
                <input type="checkbox" x-model="hasPlan" class="rounded border-gray-300 text-teal-600">
                <span class="text-sm text-gray-700">Set up a subscription now</span>
            </label>
            <div x-show="hasPlan" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                    <select name="plan_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select plan —</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — ${{ number_format($plan->price_usd,2) }}/employee/mo
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee Count</label>
                    <input type="number" min="1" name="employee_count" value="{{ old('employee_count') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                    <select name="billing_cycle" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['monthly'=>'Monthly','quarterly'=>'Quarterly (3 months)','annual'=>'Annual (12 months)'] as $val => $label)
                        <option value="{{ $val }}" {{ old('billing_cycle','monthly') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', today()->toDateString()) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        {{-- Admin Account --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Employer Admin Account</h3>
            <label class="flex items-center gap-3 cursor-pointer mb-4">
                <input type="checkbox" name="create_account" value="1" x-model="createAccount" class="rounded border-gray-300 text-teal-600">
                <span class="text-sm text-gray-700">Create login account and send welcome email</span>
            </label>
            <div x-show="createAccount" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Email</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">Create Employer</button>
            <a href="{{ route('admin.employers.index') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-600">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
