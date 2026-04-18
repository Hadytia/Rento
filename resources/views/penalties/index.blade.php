@extends('layouts.app')

@section('page_title', 'Penalties & Returns')

@section('content')

@php
    $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                    ->where('status', 1)->where('is_deleted', 0)->first();
    $isDosen = $currentAdmin && $currentAdmin->role === 'dosen';
    $canEdit = !$isDosen || ($isDosen && $currentAdmin->can_edit);
@endphp

<div class="p-6 w-full">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Penalties & Returns</h1>
        <p class="text-sm text-gray-500">Manage late returns and trigger email reminders.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Summary Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Penalties</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_penalties'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Unpaid</p>
            <p class="text-2xl font-bold text-red-500">{{ $stats['unpaid_penalties'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Total Amount</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Unpaid Amount</p>
            <p class="text-2xl font-bold text-orange-500">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex gap-6">

        {{-- LEFT: Action Needed --}}
        <div class="w-80 flex-shrink-0">
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-orange-500 text-lg">⚠️</span>
                    <span class="text-orange-600 font-semibold text-base">Action Needed</span>
                </div>
                <p class="text-sm text-gray-500 mb-4">Overdue items requiring reminders</p>

                @forelse($overdueTransactions as $trx)
                    <div class="bg-white rounded-lg p-4 shadow-sm mb-3">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-800">{{ $trx->customer_name }}</span>
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ $trx->trx_code }}</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-1">📦 {{ $trx->product_name }}</div>
                        <div class="flex items-center gap-1 text-sm text-gray-500 mb-1">
                            📅 <span>Due: {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-sm text-red-500 font-medium mb-4">
                            🕐 <span>{{ $trx->days_late }} {{ $trx->days_late == 1 ? 'Day' : 'Days' }} Late</span>
                        </div>

                        {{-- ✅ Tombol reminder hanya untuk non-dosen atau dosen yang dapat akses --}}
                        @if($canEdit)
                            <form action="{{ route('penalties.send-reminder') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transaction_id" value="{{ $trx->id }}">
                                <button type="submit"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-2 rounded-lg flex items-center justify-center gap-2 transition">
                                    ✉️ Send Email Reminder
                                </button>
                            </form>
                        @else
                            <div class="w-full bg-gray-100 text-gray-400 text-xs font-medium py-2 rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                                🔒 Tidak memiliki akses
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-4 shadow-sm text-center text-sm text-gray-400">
                        🎉 No overdue transactions!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Active Penalties Table --}}
        <div class="flex-1 bg-white rounded-xl shadow p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-1">Active Penalties & Returns</h2>
            <p class="text-sm text-gray-500 mb-4">Track and manage ongoing penalties and returns.</p>

            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b text-gray-500 text-xs uppercase">
                        <th class="py-3 pr-4">Actions</th>
                        <th class="py-3 pr-4">Customer</th>
                        <th class="py-3 pr-4">Type & Reason</th>
                        <th class="py-3 pr-4">Amount</th>
                        <th class="py-3 pr-4">Overdue</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penalties as $penalty)
                    <tr class="hover:bg-gray-50">

                        {{-- Actions --}}
                        <td class="py-3 pr-4">
                            <div class="flex gap-2">
                                @if($penalty->resolved == 0)
                                    @if($canEdit)
                                        {{-- Mark Finished --}}
                                        <form action="{{ route('penalties.finish', $penalty->id) }}" method="POST"
                                            onsubmit="return confirm('Mark this penalty as finished?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center text-xs transition"
                                                title="Mark as Finished">✓</button>
                                        </form>
                                        {{-- Mark as Paid --}}
                                        <form action="{{ route('penalties.resolve', $penalty->id) }}" method="POST"
                                            onsubmit="return confirm('Mark this penalty as paid/resolved?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center text-xs transition"
                                                title="Mark as Paid">$</button>
                                        </form>
                                    @else
                                        {{-- ✅ Dosen tanpa akses → tampil info --}}
                                        <span class="text-xs text-gray-400 italic" title="Tidak memiliki akses edit">🔒</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 italic">—</span>
                                @endif
                            </div>
                        </td>

                        {{-- Customer --}}
                        <td class="py-3 pr-4">
                            <div class="font-medium text-gray-800">{{ $penalty->customer_name }}</div>
                            <div class="text-xs text-gray-400">{{ $penalty->trx_code }}</div>
                            <div class="text-xs text-gray-400">{{ $penalty->product_name }}</div>
                        </td>

                        {{-- Type & Reason --}}
                        <td class="py-3 pr-4">
                            <div class="font-medium
                                @if($penalty->penalty_type == 'Late Return') text-orange-500
                                @elseif($penalty->penalty_type == 'Damage') text-red-500
                                @else text-gray-600 @endif">
                                {{ $penalty->penalty_type }}
                            </div>
                            <div class="text-xs text-gray-400 max-w-xs truncate" title="{{ $penalty->description }}">
                                {{ $penalty->description }}
                            </div>
                        </td>

                        {{-- Amount --}}
                        <td class="py-3 pr-4 font-medium text-gray-800">
                            Rp {{ number_format($penalty->penalty_amount, 0, ',', '.') }}
                        </td>

                        {{-- Overdue Days --}}
                        <td class="py-3 pr-4">
                            @if($penalty->overdue_days > 0)
                                <span class="text-red-500 font-medium">{{ $penalty->overdue_days }} {{ $penalty->overdue_days == 1 ? 'Day' : 'Days' }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="py-3">
                            @if($penalty->resolved == 1)
                                <span class="bg-green-100 text-green-600 text-xs font-medium px-3 py-1 rounded-full">Paid</span>
                            @else
                                <span class="bg-red-100 text-red-500 text-xs font-medium px-3 py-1 rounded-full">Unpaid</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 text-sm">
                            No penalties found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection