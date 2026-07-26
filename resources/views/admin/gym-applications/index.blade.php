<x-layouts.admin title="Applications">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Gym Applications</h1>
    <p class="text-sm text-gray-400 mt-1">Review and approve gym partner applications</p>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">ID</th>
                    <th class="table-head">Studio Name</th>
                    <th class="table-head">Contact</th>
                    <th class="table-head">Location</th>
                    <th class="table-head">Applied</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr class="table-row">
                    <td class="table-cell font-mono text-xs text-gray-400">#{{ $app->id }}</td>
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $app->studio_name }}</div>
                        @if($app->studio_name_kh)
                        <div class="text-xs text-gray-400">{{ $app->studio_name_kh }}</div>
                        @endif
                    </td>
                    <td class="table-cell">
                        <div class="text-sm text-gray-700">{{ $app->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ $app->contact_email }}</div>
                        <div class="text-xs text-gray-400">{{ $app->contact_phone }}</div>
                    </td>
                    <td class="table-cell text-sm text-gray-600">{{ $app->district }}, {{ $app->city }}</td>
                    <td class="table-cell text-xs text-gray-400">{{ $app->created_at->format('d M Y') }}</td>
                    <td class="table-cell">
                        @if($app->status === 'pending') <span class="badge-amber">Pending</span>
                        @elseif($app->status === 'approved') <span class="badge-green">Approved</span>
                        @else <span class="badge-red">Rejected</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <a href="{{ route('admin.gym-applications.show', $app) }}" class="text-brand-600 hover:text-brand-700 font-semibold text-sm">Review →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="text-4xl mb-3">📋</div>
                        <p class="text-gray-500 font-medium">No applications found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $applications->links() }}</div>
    @endif
</div>

</x-layouts.admin>
