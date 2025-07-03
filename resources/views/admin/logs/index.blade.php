@extends('layout.admin')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">📜 Nhật ký hành động Admin</h2>

    <table class="w-full border border-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">#</th>
                <th class="p-3">Người thực hiện</th>
                <th class="p-3">Hành động</th>
                <th class="p-3">Chi tiết</th>
                <th class="p-3">IP</th>
                <th class="p-3">Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr class="border-t">
                    <td class="p-3">{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                    <td class="p-3">{{ $log->admin->name ?? '---' }}</td>
                    <td class="p-3 font-semibold">{{ $log->action }}</td>
                    <td class="p-3">{{ $log->description }}</td>
                    <td class="p-3">{{ $log->ip_address }}</td>
                    <td class="p-3">{{ $log->created_at->format('H:i d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
