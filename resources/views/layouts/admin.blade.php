<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep:      #0b0f1a;
            --bg-card:      rgba(255,255,255,0.04);
            --bg-card-h:    rgba(255,255,255,0.07);
            --border:       rgba(255,255,255,0.08);
            --border-h:     rgba(255,255,255,0.18);
            --accent:       #4e73df;
            --accent-2:     #36b9cc;
            --accent-3:     #1cc88a;
            --accent-warn:  #f6c23e;
            --accent-danger:#e74a3b;
            --text-primary: #f0f2ff;
            --text-muted:   rgba(240,242,255,0.45);
            --sidebar-w:    260px;
            --topbar-h:     64px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-deep);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: rgba(11,15,26,0.95);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }

        #sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: var(--topbar-h);
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), #224abe);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-name {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--text-primary);
        }
        .sidebar-brand .brand-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-family: 'Space Mono', monospace;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-h); border-radius: 4px; }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 16px 12px 6px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .nav-item-link:hover {
            background: var(--bg-card-h);
            color: var(--text-primary);
        }
        .nav-item-link.active {
            background: linear-gradient(135deg, rgba(78,115,223,0.2), rgba(34,74,190,0.15));
            color: #fff;
            border: 1px solid rgba(78,115,223,0.25);
        }
        .nav-item-link .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--bg-card);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .nav-item-link.active .nav-icon { background: rgba(78,115,223,0.3); }
        .nav-item-link:hover .nav-icon { background: var(--bg-card-h); }

        .nav-badge {
            margin-left: auto;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            background: var(--accent);
            color: #fff;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--bg-card);
            border: 1px solid var(--border);
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .user-info .user-name { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
        .user-info .user-role { font-size: 0.68rem; color: var(--text-muted); }

        /* ── Topbar ── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(11,15,26,0.9);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            z-index: 1030;
            transition: left 0.3s cubic-bezier(.4,0,.2,1);
        }
        #topbar.expanded { left: 0; }

        .topbar-toggle {
            background: none; border: none;
            color: var(--text-muted);
            font-size: 18px;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .topbar-toggle:hover { background: var(--bg-card-h); color: var(--text-primary); }

        .topbar-breadcrumb {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .topbar-breadcrumb span { color: var(--text-primary); font-weight: 600; }

        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }
        .topbar-btn:hover { background: var(--bg-card-h); color: var(--text-primary); border-color: var(--border-h); }
        .topbar-btn .badge-dot {
            position: absolute; top: 7px; right: 7px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--accent-danger);
            border: 1.5px solid var(--bg-deep);
        }

        .logout-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            border-radius: 9px;
            background: rgba(231,74,59,0.1);
            border: 1px solid rgba(231,74,59,0.2);
            color: var(--accent-danger);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .logout-btn:hover { background: rgba(231,74,59,0.2); color: var(--accent-danger); }

        /* ── Main Content ── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 28px 28px;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
        }
        #main-content.expanded { margin-left: 0; }

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1035;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            #sidebar.mobile-open { transform: translateX(0); }
            #topbar { left: 0 !important; }
            #main-content { margin-left: 0 !important; }
            #sidebar-overlay.show { display: block; }
        }

        @media (max-width: 576px) {
            #main-content { padding: 16px; }
            .topbar-breadcrumb { display: none; }
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-h); border-radius: 4px; }

        /* ── Utilities ── */
        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            backdrop-filter: blur(10px);
        }
        .glass-card:hover { border-color: var(--border-h); }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

{{-- ── Sidebar ── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-hospital-alt"></i></div>
        <div>
            <div class="brand-name">Drl Mosaicwall</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-th-large"></i></span>
            Dashboard
        </a>

        <div class="nav-section-label">Management</div>

        <a href="{{ route('admin.doctors.index') }}"
           class="nav-item-link {{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-md"></i></span>
            Doctors
            <span class="nav-badge">{{ \App\Models\Doctor::count() }}</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>
</nav>

<header id="topbar">
    <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <div class="topbar-breadcrumb">
        Admin &nbsp;/&nbsp; <span>@yield('page-title', 'Dashboard')</span>
    </div>

    <div class="topbar-right">
        <a href="#" class="topbar-btn">
            <i class="fas fa-bell"></i>
            <span class="badge-dot"></span>
        </a>
        <a href="#" class="topbar-btn">
            <i class="fas fa-search"></i>
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span class="d-none d-sm-inline">Logout</span>
            </button>
        </form>
    </div>
</header>

{{-- ── Page Content ── --}}
<main id="main-content">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar  = document.getElementById('sidebar');
    const topbar   = document.getElementById('topbar');
    const content  = document.getElementById('main-content');
    const overlay  = document.getElementById('sidebar-overlay');
    const isMobile = () => window.innerWidth < 992;
    let collapsed  = false;

    function toggleSidebar() {
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
        } else {
            collapsed = !collapsed;
            sidebar.classList.toggle('collapsed', collapsed);
            topbar.classList.toggle('expanded', collapsed);
            content.classList.toggle('expanded', collapsed);
        }
    }

    window.addEventListener('resize', () => {
        if (!isMobile()) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        }
    });
</script>
@stack('scripts')
</body>
</html>
