<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $employer->company_name }} — Admin — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">
@include('admin._nav')

<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-6"><a href="{{ route('admin.employers.index') }}" class="text-sm text-teal-600 hover:text-teal-700">← Back</a></div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $employer->company_name }}</h1>
    <p class="text-sm text-gray-400 mb-8">Edit employer details</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.employers.update', $employer) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-4">
            <h3 class="font-semibold">Company</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $employer->company_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name (Khmer)</label>
                    <input type="text" name="company_name_kh" value="{{ old('company_name_kh', $employer->company_name_kh) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                    <select name="industry" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select —</option>
                        @foreach(['Retail','Technology','Finance','NGO','Healthcare','Education','Manufacturing','Hospitality','Other'] as $ind)
                        <option value="{{ $ind }}" {{ old('industry', $employer->industry) === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                    <select name="company_size" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select —</option>
                        @foreach(['1-10','11-50','51-100','101-250','250+'] as $sz)
                        <option value="{{ $sz }}" {{ old('company_size', $employer->company_size) === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['active','pending','suspended','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $employer->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $employer->city) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="province" value="{{ old('province', $employer->province) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1', $employer->address_line1) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-4">
            <h3 class="font-semibold">Contact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $employer->contact_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $employer->contact_email) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $employer->contact_phone) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $employer->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">Save Changes</button>
            <a href="{{ route('admin.employers.index') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
