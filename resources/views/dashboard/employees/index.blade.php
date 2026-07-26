<x-layouts.dashboard title="{{ __('employee.employees') }}">

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('employee.employees') }}</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $employer->company_name }} · {{ $employees->total() }} employees</p>
    </div>
    <a href="{{ route('dashboard.employees.create') }}" class="btn-primary text-sm">{{ __('employee.invite_new') }}</a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">{{ __('employee.name') }}</th>
                    <th class="table-head">{{ __('employee.department') }}</th>
                    <th class="table-head">{{ __('employee.card_no') }}</th>
                    <th class="table-head text-right">{{ __('dashboard.checkins_month') }}</th>
                    <th class="table-head">{{ __('employee.status') }}</th>
                    <th class="table-head">{{ __('billing.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-brand-500 text-white text-xs flex items-center justify-center font-semibold shrink-0">
                                {{ strtoupper(substr($employee->user->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">{{ $employee->user->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $employee->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell text-sm text-gray-600">{{ $employee->department ?? '—' }}</td>
                    <td class="table-cell font-mono text-xs text-gray-500">{{ $employee->membership_card_no }}</td>
                    <td class="table-cell text-right font-bold text-gray-800">{{ $employee->checkins_count }}</td>
                    <td class="table-cell">
                        @if($employee->status === 'active') <span class="badge-green">{{ __('employee.active') }}</span>
                        @elseif($employee->status === 'suspended') <span class="badge-red">{{ __('employee.suspended') }}</span>
                        @else <span class="badge-gray">{{ ucfirst($employee->status) }}</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($employee->status === 'active')
                            <form method="POST" action="{{ route('dashboard.employees.suspend', $employee) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Suspend this employee?')" class="text-red-600 hover:text-red-700 font-semibold text-sm">{{ __('employee.suspend') }}</button>
                            </form>
                        @elseif($employee->status === 'suspended')
                            <form method="POST" action="{{ route('dashboard.employees.restore', $employee) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700 font-semibold text-sm">{{ __('employee.restore') }}</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="text-4xl mb-3">👥</div>
                        <p class="text-gray-500 font-medium mb-2">No employees yet</p>
                        <a href="{{ route('dashboard.employees.create') }}" class="text-brand-600 hover:text-brand-700 text-sm font-medium">{{ __('employee.invite_new') }} →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $employees->links() }}</div>
    @endif
</div>

</x-layouts.dashboard>
