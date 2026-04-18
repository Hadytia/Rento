<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, sans-serif; background: #F0F2F5; }

        .container { display: flex; min-height: 100vh; max-width: 100% !important; }

        /* SIDEBAR */
        .sidebar {
            width: 240px; min-height: 100vh; background: #0B1A2B;
            color: #A0AEC0; padding: 20px 16px; position: fixed;
            top: 0; left: 0; bottom: 0; display: flex;
            flex-direction: column; z-index: 100;
        }
        .sidebar-logo { font-size: 20px; font-weight: 700; color: white; padding: 4px 8px 20px 8px; border-bottom: 1px solid #1E3A55; margin-bottom: 12px; }
        .sidebar-logo span { color: #FF7A00; }
        .menu { flex: 1; }
        .menu a { display: flex; align-items: center; gap: 10px; padding: 11px 12px; margin-top: 4px; text-decoration: none; color: #A0AEC0; border-radius: 8px; font-size: 14px; transition: background 0.15s, color 0.15s; }
        .menu a .menu-icon { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.7; }
        .menu a.active { background: #2D4DA3; color: white; }
        .menu a.active .menu-icon { opacity: 1; }
        .menu a:hover:not(.active) { background: #142A4A; color: #D1D9E6; }
        .sidebar-divider { border: none; border-top: 1px solid #1E3A55; margin: 12px 0; }
        .sidebar-logout { margin-top: auto; }

        /* MAIN */
        .main { flex: 1; min-width: 0; margin-left: 240px; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: white; padding: 12px 24px; border-bottom: 1px solid #E5E5E5; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50; width: 100%; }
        .topbar-title { font-size: 15px; font-weight: 600; color: #1E1E1E; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .notif-btn { width: 36px; height: 36px; border: 1px solid #E5E5E5; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: white; cursor: pointer; font-size: 16px; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: #2D4DA3; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
        .user-name { font-size: 13px; font-weight: 600; color: #1E1E1E; }
        .user-role { font-size: 11px; color: #6B6B6B; }
        .content-area { padding: 24px; flex: 1; width: 100%; min-width: 0; }
    </style>
</head>
<body>

@php
    $currentAdmin = \App\Models\Admin::where('email', Auth::user()->email)
                    ->where('status', 1)
                    ->where('is_deleted', 0)
                    ->first();
    $isDosen = $currentAdmin && $currentAdmin->role === 'dosen';
@endphp

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">Admin<span>Panel</span></div>
        <div class="menu">
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>
            <a href="/kategoris" class="{{ request()->is('kategoris*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
                Categories
            </a>
            <a href="/produk" class="{{ request()->is('produk*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                Products
            </a>
            <a href="/reports" class="{{ request()->is('reports*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    <line x1="10" y1="9" x2="8" y2="9"/>
                </svg>
                Reports &amp; Transactions
            </a>
            <a href="/penalties" class="{{ request()->is('penalties*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Penalties &amp; Returns
            </a>

            {{-- ✅ Menu Admin — hidden untuk dosen --}}
            @if(!$isDosen)
            <a href="/admins" class="{{ request()->is('admins*') ? 'active' : '' }}">
                <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Admin
            </a>
            @endif
        </div>

        <hr class="sidebar-divider">

        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}" style="margin:0; padding:0;">
                @csrf
                <button type="submit" style="display:flex; align-items:center; gap:10px; width:100%; padding:11px 12px; background:none; border:none; color:#90A1B9; cursor:pointer; font-family:Inter,sans-serif; font-size:14px; border-radius:8px;"
                    onmouseover="this.style.background='#142A4A'" onmouseout="this.style.background='none'">
                    <svg style="width:18px;height:18px;flex-shrink:0;opacity:0.7;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16,17 21,12 16,7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="topbar">
            <div class="topbar-title">
                @php
                    $titles = [
                        'dashboard'        => 'Dashboard',
                        'produks.index'    => 'Products',
                        'produks.create'   => 'Add Product',
                        'produks.edit'     => 'Edit Product',
                        'kategoris.index'  => 'Categories',
                        'reports.index'    => 'Reports & Transactions',
                        'penalties.index'  => 'Penalties & Returns',
                        'admins.index'     => 'Admin',
                        'users.index'      => 'Users',
                        'transaksi.create' => 'Buat Transaksi',
                        'transaksi.edit'   => 'Edit Transaksi',
                    ];
                    echo $titles[Route::currentRouteName()] ?? ucfirst(Route::currentRouteName() ?? 'Dashboard');
                @endphp
            </div>
            <div class="topbar-right">
                <div class="notif-btn">🔔</div>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(Auth::user()->name, ' ') ?: ' ', 1, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ $isDosen ? 'Dosen' : ucfirst(str_replace('_', ' ', $currentAdmin->role ?? 'Admin')) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-area">

            {{-- Alert Error Global --}}
            @if(session('error'))
                <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#DC2626;display:flex;align-items:center;gap:8px;">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</div>
</body>
</html>