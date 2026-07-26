<x-layouts.dashboard title="{{ __('employee.invite_heading') }}">

<a href="{{ route('dashboard.employees.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← {{ __('employee.employees') }}
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('employee.invite_heading') }}</h1>
    <p class="text-sm text-gray-400 mt-1">Add a new employee to {{ $employer->company_name }}</p>
</div>

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6 text-sm">{{ session('error') }}</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl">
    <div class="card p-6 mb-4">
        <form method="POST" action="{{ route('dashboard.employees.store') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="label">{{ __('employee.full_name') }} *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required class="input" placeholder="John Doe">
                </div>
                <div>
                    <label class="label">{{ __('employee.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="john@example.com">
                    <p class="text-xs text-gray-400 mt-1">The employee will receive login credentials at this email address</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">{{ __('employee.department') }}</label>
                        <input type="text" name="department" value="{{ old('department') }}" class="input" placeholder="e.g. Marketing">
                    </div>
                    <div>
                        <label class="label">{{ __('employee.job_title') }}</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" class="input" placeholder="e.g. Manager">
                    </div>
                </div>
                <div>
                    <label class="label">Employee Code</label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" class="input" placeholder="Optional internal employee ID">
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="btn-primary">{{ __('employee.send_invite') }}</button>
                <a href="{{ route('dashboard.employees.index') }}" class="btn-secondary">{{ __('common.cancel') }}</a>
            </div>
        </form>
    </div>

    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4">
        <p class="text-sm text-brand-700">
            <strong>Note:</strong> A membership card number will be automatically generated for this employee. They will be assigned to your company's active subscription.
        </p>
    </div>
</div>

</x-layouts.dashboard>
