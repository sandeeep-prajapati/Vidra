@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">UserPermissions</h1>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{ $item->user_permission_id ?? \$item->id ?? 'N/A' }</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{ route('rbac.show', \$item) }" class="text-indigo-600">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">No records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
