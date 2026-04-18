@extends('layouts.app')

@section('page_title', 'Reports & Transactions')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: #1E1E1E; margin: 0 0 4px 0; }
    .page-header p { font-size: 14px; color: #6B6B6B; margin: 0; }

    .toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }

    .search-wrap {
        display: flex; align-items: center; gap: 8px;
        border: 1px solid #E5E5E5; border-radius: 8px;
        padding: 0 14px; height: 42px; background: white;
        flex: 1; min-width: 200px;
    }
    .search-wrap input { border: none; outline: none; font-family: Inter, sans-serif; font-size: 14px; color: #1E1E1E; width: 100%; background: transparent; }
    .search-wrap input::placeholder { color: #9E9E9E; }

    .filter-btn {
        display: flex; align-items: center; gap: 8px;
        height: 42px; padding: 0 16px; background: white;
        border: 1px solid #E5E5E5; border-radius: 8px;
        font-family: Inter, sans-serif; font-size: 14px; color: #1E1E1E;
        cursor: pointer; white-space: nowrap;
    }
    .filter-btn:hover { background: #F5F5F5; }

    .btn-export {
        display: flex; align-items: center; gap: 8px;
        height: 42px; padding: 0 20px; background: #22C55E;
        border: none; border-radius: 8px; font-family: Inter, sans-serif;
        font-size: 14px; font-weight: 600; color: white;
        cursor: pointer; white-space: nowrap; text-decoration: none;
    }
    .btn-export:hover { background: #16A34A; color: white; }

    .btn-create {
        display: flex; align-items: center; gap: 8px;
        height: 42px; padding: 0 20px; background: #2D4DA3;
        border: none; border-radius: 8px; font-family: Inter, sans-serif;
        font-size: 14px; font-weight: 600; color: white;
        cursor: pointer; white-space: nowrap; text-decoration: none;
    }
    .btn-create:hover { background: #243F8F; color: white; }

    .table-container { background: white; border-radius: 12px; padding: 20px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); }
    .table-title { font-size: 16px; font-weight: 600; color: #1E1E1E; margin-bottom: 16px; }

    table { width: 100%; border-collapse: collapse; }
    thead th { font-family: Inter, sans-serif; font-size: 13px; font-weight: 600; color: #6B6B6B; padding: 10px 16px; text-align: left; border-bottom: 1px solid #F0F0F0; }
    tbody tr { border-bottom: 1px solid #F5F5F5; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFAFA; }
    tbody td { font-family: Inter, sans-serif; font-size: 14px; color: #1E1E1E; padding: 14px 16px; vertical-align: middle; }

    .trx-id { font-weight: 600; color: #1E1E1E; }
    .customer-name { font-weight: 600; }
    .period-text { font-size: 13px; color: #6B6B6B; }
    .amount { font-weight: 600; color: #1E1E1E; }

    .badge { display: inline-block; border-radius: 20px; padding: 5px 14px; font-size: 13px; font-weight: 600; font-family: Inter, sans-serif; }
    .badge-active    { background: #EFF6FF; color: #2D4DA3; border: 1px solid #BFDBFE; }
    .badge-completed { background: #ECFDF5; color: #059669; border: 1px solid #6EE7B7; }
    .badge-overdue   { background: #FFF7ED; color: #EA580C; border: 1px solid #FED7AA; }
    .badge-cancelled { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

    .action-wrap { display: flex; gap: 6px; }
    .action-btn {
        width: 34px; height: 34px; border: 1px solid #E5E5E5;
        border-radius: 8px; background: white; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #6B6B6B; transition: all 0.15s; text-decoration: none;
    }
    .action-btn:hover { background: #F5F5F5; border-color: #D0D0D0; }
    .action-btn.btn-return { color: #059669; border-color: #6EE7B7; }
    .action-btn.btn-return:hover { background: #ECFDF5; }
    .action-btn.btn-edit { color: #2D4DA3; border-color: #BFDBFE; }
    .action-btn.btn-edit:hover { background: #EFF6FF; }
    .action-btn.btn-delete { color: #DC2626; border-color: #FECACA; }
    .action-btn.btn-delete:hover { background: #FEF2F2; }
    .action-btn svg { width: 16px; height: 16px; }

    .alert-success { background: #ECFDF5; border: 1px solid #6EE7B7; border-radius: 8px; padding: 10px 16px; font-size: 13px; color: #065F46; margin-bottom: 16px; }

    .empty-state { text-align: center; padding: 48px 0; color: #9E9E9E; }
    .empty-state svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.3; }
    .empty-state p { font-size: 14px; margin: 0; }
</style>

<div class="page-header">
    <h1>Reports &amp; Transactions</h1>
    <p>View and export rental histories.</p>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- Toolbar --}}
<div class="toolbar">
    <div class="search-wrap">
        <svg width="16" height="16" fill="none" stroke="#9E9E9E" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Search transaksi..." id="searchInput" onkeyup="filterTable()">
    </div>

    <button class="filter-btn" id="statusFilterBtn" onclick="cycleStatus()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"/>
        </svg>
        <span id="statusLabel">Status: All</span>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="6,9 12,15 18,9"/>
        </svg>
    </button>

    <a href="{{ route('reports.export') }}" class="btn-export">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7,10 12,15 17,10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Export CSV
    </a>

    @php
        $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                        ->where('status', 1)->where('is_deleted', 0)->first();
        $isDosen = $currentAdmin && $currentAdmin->role === 'dosen';
        $isSuperadmin = $currentAdmin && $currentAdmin->role === 'superadmin';
        $canEdit = $currentAdmin && ($currentAdmin->can_edit == 1 || $isSuperadmin);
    @endphp

    @if(!$isDosen && $canEdit)
    <a href="{{ route('transaksi.create') }}" class="btn-create">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Buat Transaksi
    </a>
    @endif
</div>

{{-- Table --}}
<div class="table-container">
    <div class="table-title">Recent Transactions</div>

    <table id="transactionTable">
        <thead>
            <tr>
                <th>Actions</th>
                <th>TRX ID</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Period</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $trx)
            <tr data-status="{{ strtolower($trx->trx_status) }}">
                <td>
                    <div class="action-wrap">
                        {{-- View Detail --}}
                        <button class="action-btn" title="Lihat Detail"
                            onclick="openDetail(
                                '{{ $trx->trx_code }}',
                                '{{ addslashes($trx->user->name ?? '-') }}',
                                '{{ addslashes($trx->user->email ?? '-') }}',
                                '{{ addslashes($trx->user->phone ?? '-') }}',
                                '{{ addslashes($trx->user->address ?? '-') }}',
                                '{{ addslashes($trx->user->emergency_contact ?? '-') }}',
                                '{{ addslashes($trx->product->product_name ?? '-') }}',
                                '{{ $trx->rental_start }}',
                                '{{ $trx->rental_end }}',
                                '{{ number_format($trx->total_amount, 0, chr(44), chr(46)) }}',
                                '{{ number_format($trx->paid_amount, 0, chr(44), chr(46)) }}',
                                '{{ addslashes($trx->payment_method ?? '-') }}',
                                '{{ strtolower($trx->trx_status) }}'
                            )">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>

                        {{-- Download --}}
                        <a class="action-btn" href="{{ route('reports.download', $trx->id) }}" title="Download CSV">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7,10 12,15 17,10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                        </a>

                        @if(!$isDosen && ($canEdit || $isSuperadmin))
                        {{-- Edit --}}
                        <a class="action-btn btn-edit" href="{{ route('transaksi.edit', $trx->id) }}" title="Edit Transaksi">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>

                        {{-- Return (hanya untuk status Active/Overdue) --}}
                        @if(in_array($trx->trx_status, ['Active', 'Overdue']))
                        <form method="POST" action="{{ route('transaksi.return', $trx->id) }}"
                              onsubmit="return confirm('Konfirmasi pengembalian {{ $trx->trx_code }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-btn btn-return" title="Kembalikan Barang">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="1,4 1,10 7,10"/>
                                    <path d="M3.51 15a9 9 0 1 0 .49-3.45"/>
                                </svg>
                            </button>
                        </form>
                        @endif

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('transaksi.destroy', $trx->id) }}"
                              onsubmit="return confirm('Hapus transaksi {{ $trx->trx_code }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-delete" title="Hapus Transaksi">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3,6 5,6 21,6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
                <td><span class="trx-id">{{ $trx->trx_code }}</span></td>
                <td><span class="customer-name">{{ $trx->user->name ?? '-' }}</span></td>
                <td>{{ $trx->product->product_name ?? '-' }}</td>
                <td>
                    <span class="period-text">
                        {{ \Carbon\Carbon::parse($trx->rental_start)->format('d M Y') }}
                        to
                        {{ \Carbon\Carbon::parse($trx->rental_end)->format('d M Y') }}
                    </span>
                </td>
                <td>
                    <span class="amount">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                </td>
                <td>
                    @php
                        $badgeClass = match(strtolower($trx->trx_status)) {
                            'active'    => 'badge-active',
                            'completed' => 'badge-completed',
                            'overdue'   => 'badge-overdue',
                            'cancelled' => 'badge-cancelled',
                            default     => 'badge-active',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $trx->trx_status }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                        </svg>
                        <p>Belum ada transaksi.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── MODAL DETAIL ── --}}
<div class="modal-overlay" id="modalDetail">
    <div class="detail-box">

        {{-- Header --}}
        <div class="detail-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="detail-trx-id" id="dTrxId">TRX-001</span>
                <span class="detail-title">Transaction Details</span>
            </div>
            <button onclick="closeDetail()" style="width:30px;height:30px;background:#F5F5F5;border:1px solid #E5E5E5;border-radius:6px;font-size:16px;cursor:pointer;color:#6B6B6B;display:flex;align-items:center;justify-content:center;">✕</button>
        </div>

        {{-- Customer Information --}}
        <div class="detail-section-title">Customer Information</div>
        <div class="detail-card">
            <div class="customer-left">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                    <div class="customer-avatar" id="dAvatar">BS</div>
                    <div>
                        <div class="customer-name" id="dName">-</div>
                    </div>
                </div>
                <div style="display:flex; gap:8px; align-items:flex-start;">
                    <span style="font-size:13px; color:#6B6B6B; min-width:36px;">Email</span>
                    <span style="font-size:13px; color:#2D4DA3;" id="dEmail">-</span>
                </div>
            </div>
            <div class="customer-right">
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-val" id="dAddress">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomor HP</div>
                    <div class="info-val" id="dPhone">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kontak Darurat</div>
                    <div class="info-val" id="dEmergency">-</div>
                </div>
            </div>
        </div>

        {{-- Rental Information --}}
        <div class="detail-section-title">Rental Information</div>
        <div class="detail-card">
            <div class="rental-left">
                <div class="rental-meta">
                    <div class="meta-row"><span class="meta-label">Rental Item</span><span class="meta-val" id="dItem">-</span></div>
                    <div class="meta-row"><span class="meta-label">TRX ID</span><span class="meta-val" id="dTrxId2">-</span></div>
                    <div class="meta-row">
                        <span class="meta-label">Status</span>
                        <span class="badge badge-active" id="dStatusBadge">-</span>
                    </div>
                    <div class="meta-row"><span class="meta-label">Period</span><span class="meta-val" id="dPeriod">-</span></div>
                </div>
            </div>
            <div class="rental-right">
                <div class="info-row">
                    <div class="info-label">Total Amount</div>
                    <div class="info-val" id="dAmount">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Paid Amount</div>
                    <div class="info-val" id="dPaid">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Payment Method</div>
                    <div class="info-val" id="dPayment">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rental Period</div>
                    <div class="info-val" id="dPeriod2">-</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <span class="badge badge-active" id="dStatusBadge2">-</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="detail-footer">
            <button class="btn-close-detail" onclick="closeDetail()">Close</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.show { display: flex; }
    .detail-box { background: #FFFFFF; border-radius: 14px; width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; box-shadow: 0px 20px 50px rgba(0,0,0,0.2); }
    .detail-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 20px 14px; border-bottom: 1px solid #F0F0F0; }
    .detail-trx-id { font-size: 13px; font-weight: 700; color: #6B6B6B; }
    .detail-title { font-size: 17px; font-weight: 700; color: #1E1E1E; }
    .detail-section-title { font-size: 13px; font-weight: 700; color: #1E1E1E; padding: 14px 20px 8px; }
    .detail-card { margin: 0 20px 14px; border: 1px solid #E5E5E5; border-radius: 10px; display: flex; overflow: hidden; }
    .customer-left { flex: 0 0 200px; padding: 14px; border-right: 1px solid #E5E5E5; }
    .customer-avatar { width: 40px; height: 40px; border-radius: 50%; background: #D1D5DB; color: #374151; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; }
    .customer-right { flex: 1; padding: 14px; }
    .rental-left { flex: 0 0 200px; padding: 14px; border-right: 1px solid #E5E5E5; }
    .rental-meta { display: flex; flex-direction: column; gap: 6px; }
    .meta-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .meta-label { font-size: 12px; color: #9E9E9E; white-space: nowrap; }
    .meta-val { font-size: 12px; color: #1E1E1E; font-weight: 600; }
    .rental-right { flex: 1; padding: 14px; }
    .info-row { margin-bottom: 10px; }
    .info-label { font-size: 12px; color: #9E9E9E; margin-bottom: 1px; }
    .info-val { font-size: 13px; color: #1E1E1E; font-weight: 500; }
    .detail-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 14px 20px; border-top: 1px solid #F0F0F0; }
    .btn-close-detail { height: 36px; padding: 0 20px; background: white; border: 1px solid #E5E5E5; border-radius: 8px; font-family: Inter, sans-serif; font-size: 13px; cursor: pointer; color: #1E1E1E; }
    .btn-close-detail:hover { background: #F5F5F5; }
</style>

<script>
    // Search filter
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows  = document.querySelectorAll('#transactionTable tbody tr[data-status]');
        rows.forEach(row => {
            const matchSearch = row.innerText.toLowerCase().includes(input);
            const matchStatus = currentStatus === 'all' || row.dataset.status === currentStatus;
            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    // Status cycle filter
    const statuses      = ['all', 'active', 'completed', 'overdue', 'cancelled'];
    const statusLabels  = { all: 'Status: All', active: 'Active', completed: 'Completed', overdue: 'Overdue', cancelled: 'Cancelled' };
    let currentStatus   = 'all';
    let statusIdx       = 0;

    function cycleStatus() {
        statusIdx     = (statusIdx + 1) % statuses.length;
        currentStatus = statuses[statusIdx];
        document.getElementById('statusLabel').textContent = statusLabels[currentStatus];
        filterTable();
    }

    // Detail modal — menerima semua field dari DB
    function openDetail(trxCode, name, email, phone, address, emergency, item, start, end, amount, paid, payment, status) {
        const initials = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();

        document.getElementById('dTrxId').textContent   = trxCode;
        document.getElementById('dTrxId2').textContent  = trxCode;
        document.getElementById('dName').textContent    = name;
        document.getElementById('dAvatar').textContent  = initials;
        document.getElementById('dEmail').textContent   = email;
        document.getElementById('dPhone').textContent   = phone;
        document.getElementById('dAddress').textContent = address;
        document.getElementById('dEmergency').textContent = emergency;
        document.getElementById('dItem').textContent    = item;

        const period = start + ' to ' + end;
        document.getElementById('dPeriod').textContent  = period;
        document.getElementById('dPeriod2').textContent = period;
        document.getElementById('dAmount').textContent  = 'Rp ' + amount;
        document.getElementById('dPaid').textContent    = 'Rp ' + paid;
        document.getElementById('dPayment').textContent = payment;

        const badgeMap = {
            active:    ['badge-active',    'Active'],
            completed: ['badge-completed', 'Completed'],
            overdue:   ['badge-overdue',   'Overdue'],
            cancelled: ['badge-cancelled', 'Cancelled'],
        };
        const [cls, label] = badgeMap[status] || ['badge-active', status];
        ['dStatusBadge', 'dStatusBadge2'].forEach(id => {
            const el = document.getElementById(id);
            el.className  = 'badge ' + cls;
            el.textContent = label;
        });

        document.getElementById('modalDetail').classList.add('show');
    }

    function closeDetail() {
        document.getElementById('modalDetail').classList.remove('show');
    }

    document.getElementById('modalDetail').addEventListener('click', function(e) {
        if (e.target === this) closeDetail();
    });
</script>

@endsection