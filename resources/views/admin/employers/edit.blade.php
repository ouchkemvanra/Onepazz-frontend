<x-layouts.admin title="{{ $employer->company_name }}">

<a href="{{ route('admin.employers.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Employers
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ $employer->company_name }}</h1>
    <p class="text-sm text-gray-400 mt-1">Edit employer details</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 text-sm">{{ session('success') }}</div>
@endif

<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.employers.update', $employer) }}">
        @csrf @method('PUT')

        <div class="card p-6 mb-5 space-y-4">
            <h3 class="font-bold text-gray-900">Company</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Company Name *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $employer->company_name) }}" required class="input">
                </div>
                <div>
                    <label class="label">Company Name (Khmer)</label>
                    <input type="text" name="company_name_kh" value="{{ old('company_name_kh', $employer->company_name_kh) }}" class="input">
                </div>
                <div>
                    <label class="label">Industry</label>
                    <select name="industry" class="input">
                        <option value="">— Select —</option>
                        @foreach(['Retail','Technology','Finance','NGO','Healthcare','Education','Manufacturing','Hospitality','Other'] as $ind)
                        <option value="{{ $ind }}" {{ old('industry', $employer->industry) === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Company Size</label>
                    <select name="company_size" class="input">
                        <option value="">— Select —</option>
                        @foreach(['1-10','11-50','51-100','101-250','250+'] as $sz)
                        <option value="{{ $sz }}" {{ old('company_size', $employer->company_size) === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Status</label>
                    <select name="status" required class="input">
                        @foreach(['active','pending','suspended','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $employer->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">City</label>
                    <input type="text" name="city" value="{{ old('city', $employer->city) }}" class="input">
                </div>
                <div>
                    <label class="label">Province</label>
                    <input type="text" name="province" value="{{ old('province', $employer->province) }}" class="input">
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Address</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1', $employer->address_line1) }}" class="input">
                </div>
            </div>
        </div>

        <div class="card p-6 mb-5 space-y-4">
            <h3 class="font-bold text-gray-900">Contact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Name *</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $employer->contact_name) }}" required class="input">
                </div>
                <div>
                    <label class="label">Email *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $employer->contact_email) }}" required class="input">
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $employer->contact_phone) }}" class="input">
                </div>
            </div>
        </div>

        <div class="card p-6 mb-6">
            <label class="label">Internal Notes</label>
            <textarea name="notes" rows="3" class="input">{{ old('notes', $employer->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('admin.employers.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-layouts.admin>
