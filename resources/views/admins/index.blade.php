@extends('layouts.app')

@section('content')

<div class="p-6 w-full">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Kelola Akun Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun administrator, atur hak akses, dan lacak aktivitas audit.</p>
        </div>
        <button onclick="openAddModal()"
            class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 h-9 rounded-lg flex items-center gap-2 whitespace-nowrap">
            + Tambah Data Admin
        </button>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2.5 text-sm text-green-700 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ── SECTION PENDING DOSEN ── --}}
    @if($pendingDosens->count() > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-orange-500 text-lg">⏳</span>
            <span class="text-orange-600 font-semibold text-base">Permintaan Akses Dosen</span>
            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-1">{{ $pendingDosens->count() }}</span>
        </div>
        <p class="text-sm text-gray-500 mb-4">Dosen berikut mendaftar via Google dan menunggu persetujuan.</p>

        <div class="flex flex-col gap-3">
            @foreach($pendingDosens as $dosen)
            @php $avColors = ['bg-blue-700','bg-purple-600','bg-emerald-600','bg-amber-600','bg-red-600']; @endphp
            <div class="bg-white rounded-lg p-4 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full {{ $avColors[$dosen->id % 5] }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($dosen->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 text-sm">{{ $dosen->name }}</div>
                        <div class="text-xs text-gray-500">{{ $dosen->email }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Daftar: {{ \Carbon\Carbon::parse($dosen->created_date)->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Approve --}}
                    <button type="button"
                        onclick="openConfirmModal('approve', {{ $dosen->id }}, '{{ addslashes($dosen->name) }}')"
                        class="h-8 px-3 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg flex items-center gap-1">
                        ✅ ACC
                    </button>
                    {{-- Reject --}}
                    <button type="button"
                        onclick="openConfirmModal('reject', {{ $dosen->id }}, '{{ addslashes($dosen->name) }}')"
                        class="h-8 px-3 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg flex items-center gap-1">
                        ❌ Tolak
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-gray-800">Tabel Admin</span>
            <div class="flex items-center border border-gray-200 rounded-lg px-3 gap-2 h-9 w-52">
                <span class="text-gray-400 text-sm">🔍</span>
                <input type="text" placeholder="Cari admin..." id="searchInput" onkeyup="filterTable()"
                    class="border-none outline-none text-sm text-gray-800 w-full placeholder-gray-400">
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full text-sm" id="adminTable">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Aksi</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Identitas Admin</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Akun & Autentikasi</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Role & Hak Akses</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Akses Edit</th>
                    <th class="text-xs font-semibold text-gray-500 px-3 py-2.5 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $avColors = ['bg-blue-700', 'bg-purple-600', 'bg-emerald-600', 'bg-amber-600', 'bg-red-600'];
                @endphp
                @forelse ($admins as $admin)
                @php
                    $avColor = $avColors[$admin->id % 5];
                    $roleLabel = match($admin->role) {
                        'superadmin' => 'Super Admin',
                        'admin'      => 'Admin',
                        'staff'      => 'Staff',
                        'dosen'      => 'Dosen',
                        default      => ucfirst($admin->role),
                    };
                    $accessLabel = match($admin->role) {
                        'superadmin' => 'Semua Akses',
                        'admin'      => 'Kelola Produk',
                        'staff'      => 'Terbatas',
                        'dosen'      => 'View Only',
                        default      => 'Terbatas',
                    };
                    $accessClass = match($admin->role) {
                        'superadmin' => 'bg-purple-100 text-purple-700',
                        'admin'      => 'bg-blue-100 text-blue-700',
                        'dosen'      => 'bg-yellow-100 text-yellow-700',
                        default      => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    {{-- Aksi --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1">
                            <button onclick="openEditModal(
                                {{ $admin->id }},
                                '{{ addslashes($admin->name) }}',
                                '{{ addslashes($admin->email) }}',
                                '{{ $admin->role }}',
                                '{{ $admin->status }}'
                            )" class="w-7 h-7 rounded-md bg-indigo-100 text-indigo-700 hover:bg-indigo-200 flex items-center justify-center text-xs">✏️</button>
                            <button type="button"
                                onclick="openConfirmModal('delete', {{ $admin->id }}, '{{ addslashes($admin->name) }}')"
                                class="w-7 h-7 rounded-md bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center text-xs">🗑️</button>
                        </div>
                    </td>

                    {{-- Identitas --}}
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full {{ $avColor }} text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $admin->name }}</div>
                                <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Autentikasi --}}
                    <td class="px-3 py-3">
                        <div class="text-xs text-gray-500">Username</div>
                        <div class="text-sm text-gray-800">{{ strtolower(explode('@', $admin->email)[0]) }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Dibuat: {{ \Carbon\Carbon::parse($admin->created_date)->format('d M Y') }}
                        </div>
                    </td>

                    {{-- Role --}}
                    <td class="px-3 py-3">
                        <div class="font-semibold text-gray-900 mb-1">{{ $roleLabel }}</div>
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded {{ $accessClass }}">{{ $accessLabel }}</span>
                    </td>

                    {{-- Toggle Akses Edit --}}
                    <td class="px-3 py-3">
                        @if(in_array($admin->role, ['superadmin', 'admin', 'staff']))
                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                ✅ Full
                            </span>
                        @else
                            <button type="button"
                                onclick="openConfirmModal('toggle', {{ $admin->id }}, '{{ addslashes($admin->name) }}', {{ $admin->can_edit ? 'true' : 'false' }})"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full transition
                                    {{ $admin->can_edit
                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $admin->can_edit ? '✅ Diberikan' : '🔒 View Only' }}
                            </button>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                            {{ $admin->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $admin->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Belum ada data admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL KONFIRMASI MODERN ── --}}
<div id="modalConfirm" class="hidden fixed inset-0 z-50 items-center justify-center" style="background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);">
    <div style="background:white;border-radius:20px;width:400px;box-shadow:0 25px 60px rgba(0,0,0,0.2);overflow:hidden;">

        {{-- Top accent bar --}}
        <div id="confirmAccent" style="height:4px;width:100%;"></div>

        <div style="padding:28px 28px 24px;">
            {{-- Icon + Title --}}
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div id="confirmIconWrap" style="width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">
                    <span id="confirmIcon">⚠️</span>
                </div>
                <div>
                    <div id="confirmTitle" style="font-size:16px;font-weight:700;color:#0F172A;line-height:1.3;">Konfirmasi</div>
                    <div id="confirmSubtitle" style="font-size:12px;color:#94A3B8;margin-top:1px;"></div>
                </div>
            </div>

            {{-- Divider --}}
            <div style="height:1px;background:#F1F5F9;margin-bottom:14px;"></div>

            {{-- Description --}}
            <p id="confirmDesc" style="font-size:13.5px;color:#475569;line-height:1.6;margin-bottom:22px;"></p>

            {{-- Buttons --}}
            <div style="display:flex;gap:10px;">
                <button onclick="closeConfirmModal()"
                    style="flex:1;height:42px;border:1.5px solid #E2E8F0;border-radius:12px;font-size:13px;font-weight:600;color:#64748B;background:white;cursor:pointer;transition:all 0.15s;"
                    onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='white'">
                    Batal
                </button>
                <button id="confirmBtn" onclick="executeConfirm()"
                    style="flex:1;height:42px;border:none;border-radius:12px;font-size:13px;font-weight:700;color:white;cursor:pointer;transition:all 0.15s;">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden forms --}}
<form id="formApprove" method="POST" action="" class="hidden">@csrf @method('PATCH')</form>
<form id="formReject"  method="POST" action="" class="hidden">@csrf @method('PATCH')</form>
<form id="formToggle"  method="POST" action="" class="hidden">@csrf @method('PATCH')</form>
<form id="formDelete"  method="POST" action="" class="hidden">@csrf @method('DELETE')</form>

{{-- MODAL ADD --}}
<div class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center" id="modalAdd">
    <div class="bg-white rounded-xl p-8 w-[480px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Tambah Data Admin</h2>
            <button onclick="closeModal('modalAdd')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" action="{{ route('admins.store') }}">
            @csrf

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas Admin</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" placeholder="Masukkan nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" placeholder="Masukkan email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Akun & Autentikasi</p>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                    <input type="password" name="password" placeholder="Password..." required
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi..."
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Role & Hak Akses</p>
            <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 mb-3">
                <option value="" disabled selected>— Pilih Role —</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="dosen">Dosen</option>
            </select>

            <input type="hidden" name="status" id="addStatusValue" value="1">
            <div class="flex items-center justify-between mb-3 mt-4">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <div class="w-10 h-5 bg-blue-600 rounded-full relative transition-colors" id="toggleSlider"
                        onclick="toggleAddStatus()">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 transition-all" id="toggleThumb" style="left:22px"></div>
                    </div>
                    <span class="text-sm text-gray-800" id="toggleLabel">Aktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('modalAdd')"
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-800">Batal</button>
                <button type="submit"
                    class="h-9 px-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center" id="modalEdit">
    <div class="bg-white rounded-xl p-8 w-[480px] max-h-[90vh] overflow-y-auto shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-gray-900">Edit Data Admin</h2>
            <button onclick="closeModal('modalEdit')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">✕</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')

            <p class="text-xs font-semibold text-gray-800 mb-3">Identitas Admin</p>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                <input type="text" name="name" id="editName" placeholder="Masukkan nama lengkap..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>
            <div class="relative mb-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">✉️</span>
                <input type="email" name="email" id="editEmail" placeholder="Masukkan email..." required
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Akun & Autentikasi</p>
            <div class="relative mb-3">
                <span onclick="togglePasswordFields()" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-blue-700 font-semibold cursor-pointer">Ubah password</span>
                <div class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-400">••••••••</div>
            </div>
            <div id="passwordFields" class="hidden">
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password" placeholder="Password baru..."
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi..."
                            class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <p class="text-xs font-semibold text-gray-800 mb-3 mt-4">Role & Hak Akses</p>
            <select name="role" id="editRole" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-blue-500 mb-3">
                <option value="" disabled>— Pilih Role —</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="dosen">Dosen</option>
            </select>

            <input type="hidden" name="status" id="editStatusValue" value="0">
            <div class="flex items-center justify-between mb-3 mt-4">
                <span class="text-sm font-semibold text-gray-800">Status Akun</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <div class="w-10 h-5 bg-gray-200 rounded-full relative transition-colors" id="editToggleSlider"
                        onclick="toggleEditStatus()">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 left-0.5 transition-all" id="editToggleThumb"></div>
                    </div>
                    <span class="text-sm text-gray-800" id="editToggleLabel">Nonaktif</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal('modalEdit')"
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-sm text-gray-800">Batal</button>
                <button type="submit"
                    class="h-9 px-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── CONFIRM MODAL ──────────────────────────────────────────────────────────
    let confirmAction = null;

    const confirmConfig = {
        approve: {
            icon: '✅', iconBg: '#DCFCE7', accentBg: '#22C55E',
            subtitle: 'Persetujuan Akses',
            title: 'Setujui Akses Dosen',
            btnBg: '#22C55E', btnHover: '#16A34A',
            desc: (name) => `Akun <strong style="color:#0F172A">${name}</strong> akan disetujui dan dapat login ke sistem sebagai Dosen.`,
        },
        reject: {
            icon: '🚫', iconBg: '#FEE2E2', accentBg: '#EF4444',
            subtitle: 'Penolakan Akses',
            title: 'Tolak Akses Dosen',
            btnBg: '#EF4444', btnHover: '#DC2626',
            desc: (name) => `Akun <strong style="color:#0F172A">${name}</strong> akan ditolak dan tidak dapat mengakses sistem.`,
        },
        toggle_on: {
            icon: '🔓', iconBg: '#DBEAFE', accentBg: '#3B82F6',
            subtitle: 'Manajemen Hak Akses',
            title: 'Berikan Akses Edit',
            btnBg: '#3B82F6', btnHover: '#2563EB',
            desc: (name) => `<strong style="color:#0F172A">${name}</strong> akan mendapatkan hak akses edit konten di seluruh sistem.`,
        },
        toggle_off: {
            icon: '🔒', iconBg: '#F1F5F9', accentBg: '#64748B',
            subtitle: 'Manajemen Hak Akses',
            title: 'Cabut Akses Edit',
            btnBg: '#64748B', btnHover: '#475569',
            desc: (name) => `Akses edit <strong style="color:#0F172A">${name}</strong> akan dicabut. Akun kembali ke mode <em>View Only</em>.`,
        },
        delete: {
            icon: '🗑️', iconBg: '#FEE2E2', accentBg: '#EF4444',
            subtitle: 'Hapus Data Admin',
            title: 'Hapus Admin',
            btnBg: '#EF4444', btnHover: '#DC2626',
            desc: (name) => `Akun <strong style="color:#0F172A">${name}</strong> akan dihapus secara permanen dan tidak dapat dipulihkan.`,
        },
    };

    function openConfirmModal(type, id, name, canEdit = false) {
        const key = type === 'toggle' ? (canEdit ? 'toggle_off' : 'toggle_on') : type;
        const cfg = confirmConfig[key];

        const routes = {
            approve: `/admins/${id}/approve`,
            reject:  `/admins/${id}/reject`,
            toggle:  `/admins/${id}/toggle-edit`,
            delete:  `/admins/${id}`,
        };
        confirmAction = { formId: `form${type.charAt(0).toUpperCase() + type.slice(1)}`, action: routes[type] };

        document.getElementById('confirmIcon').textContent          = cfg.icon;
        document.getElementById('confirmIconWrap').style.background = cfg.iconBg;
        document.getElementById('confirmAccent').style.background   = cfg.accentBg;
        document.getElementById('confirmTitle').textContent         = cfg.title;
        document.getElementById('confirmSubtitle').textContent      = cfg.subtitle;
        document.getElementById('confirmDesc').innerHTML            = cfg.desc(name);
        document.getElementById('confirmBtn').style.background      = cfg.btnBg;
        document.getElementById('confirmBtn').onmouseover           = () => document.getElementById('confirmBtn').style.background = cfg.btnHover;
        document.getElementById('confirmBtn').onmouseout            = () => document.getElementById('confirmBtn').style.background = cfg.btnBg;

        document.getElementById('modalConfirm').classList.replace('hidden', 'flex');
    }

    function closeConfirmModal() {
        document.getElementById('modalConfirm').classList.replace('flex', 'hidden');
        confirmAction = null;
    }

    function executeConfirm() {
        if (!confirmAction) return;
        const form = document.getElementById(confirmAction.formId);
        form.action = confirmAction.action;
        form.submit();
    }

    document.getElementById('modalConfirm').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });

    // ── ADD / EDIT MODAL ───────────────────────────────────────────────────────
    let addStatusActive = true;
    function toggleAddStatus() {
        addStatusActive = !addStatusActive;
        const slider = document.getElementById('toggleSlider');
        const thumb  = document.getElementById('toggleThumb');
        const label  = document.getElementById('toggleLabel');
        document.getElementById('addStatusValue').value = addStatusActive ? 1 : 0;
        if (addStatusActive) {
            slider.classList.replace('bg-gray-200', 'bg-blue-600');
            thumb.style.left = '22px';
            label.textContent = 'Aktif';
        } else {
            slider.classList.replace('bg-blue-600', 'bg-gray-200');
            thumb.style.left = '2px';
            label.textContent = 'Nonaktif';
        }
    }

    let editStatusActive = false;
    function toggleEditStatus() {
        editStatusActive = !editStatusActive;
        const slider = document.getElementById('editToggleSlider');
        const thumb  = document.getElementById('editToggleThumb');
        const label  = document.getElementById('editToggleLabel');
        document.getElementById('editStatusValue').value = editStatusActive ? 1 : 0;
        if (editStatusActive) {
            slider.classList.replace('bg-gray-200', 'bg-blue-600');
            thumb.style.left = '22px';
            label.textContent = 'Aktif';
        } else {
            slider.classList.replace('bg-blue-600', 'bg-gray-200');
            thumb.style.left = '2px';
            label.textContent = 'Nonaktif';
        }
    }

    function togglePasswordFields() {
        document.getElementById('passwordFields').classList.toggle('hidden');
    }

    function openAddModal() {
        addStatusActive = true;
        document.getElementById('addStatusValue').value = 1;
        document.getElementById('toggleSlider').classList.replace('bg-gray-200', 'bg-blue-600');
        document.getElementById('toggleThumb').style.left = '22px';
        document.getElementById('toggleLabel').textContent = 'Aktif';
        document.getElementById('modalAdd').classList.replace('hidden', 'flex');
    }

    function openEditModal(id, name, email, role, status) {
        document.getElementById('editForm').action = '/admins/' + id;
        document.getElementById('editName').value  = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value  = role;

        editStatusActive = status == 1;
        document.getElementById('editStatusValue').value = editStatusActive ? 1 : 0;

        const slider = document.getElementById('editToggleSlider');
        const thumb  = document.getElementById('editToggleThumb');
        const label  = document.getElementById('editToggleLabel');

        if (editStatusActive) {
            slider.classList.replace('bg-gray-200', 'bg-blue-600');
            thumb.style.left = '22px';
            label.textContent = 'Aktif';
        } else {
            slider.classList.replace('bg-blue-600', 'bg-gray-200');
            thumb.style.left = '2px';
            label.textContent = 'Nonaktif';
        }

        document.getElementById('modalEdit').classList.replace('hidden', 'flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.replace('flex', 'hidden');
        document.getElementById('passwordFields').classList.add('hidden');
    }

    document.querySelectorAll('#modalAdd, #modalEdit').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#adminTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }
</script>

@endsection