@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        /* ── Stat Cards ── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: all 0.25s;
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.07;
            transform: translate(20px, -20px);
        }
        .stat-card:hover { border-color: var(--border-h); transform: translateY(-2px); }

        .stat-card.blue::before   { background: var(--accent); }
        .stat-card.teal::before   { background: var(--accent-2); }
        .stat-card.green::before  { background: var(--accent-3); }
        .stat-card.yellow::before { background: var(--accent-warn); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: rgba(78,115,223,0.15);  color: var(--accent); }
        .stat-icon.teal   { background: rgba(54,185,204,0.15);  color: var(--accent-2); }
        .stat-icon.green  { background: rgba(28,200,138,0.15);  color: var(--accent-3); }
        .stat-icon.yellow { background: rgba(246,194,62,0.15);  color: var(--accent-warn); }

        .stat-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 1.9rem; font-weight: 700; line-height: 1; color: var(--text-primary); }
        .stat-delta { font-size: 0.72rem; color: var(--accent-3); margin-top: 4px; }
        .stat-delta.down { color: var(--accent-danger); }

        /* ── Section Title ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::before {
            content: '';
            width: 4px; height: 18px;
            border-radius: 4px;
            background: linear-gradient(180deg, var(--accent), var(--accent-2));
        }
        .view-all-link {
            font-size: 0.78rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .view-all-link:hover { opacity: 1; color: var(--accent); }

        /* ── Recent Doctors Table ── */
        .dash-table { width: 100%; border-collapse: collapse; }
        .dash-table th {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 8px 14px;
            border-bottom: 1px solid var(--border);
        }
        .dash-table td {
            padding: 13px 14px;
            font-size: 0.855rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }
        .dash-table tr:last-child td { border-bottom: none; }
        .dash-table tr:hover td { background: var(--bg-card-h); }

        .doc-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .badge-status {
            font-size: 0.68rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: 0.04em;
        }
        .badge-active   { background: rgba(28,200,138,0.15); color: var(--accent-3); border: 1px solid rgba(28,200,138,0.2); }
        .badge-inactive { background: rgba(231,74,59,0.12);  color: var(--accent-danger); border: 1px solid rgba(231,74,59,0.2); }

        /* ── Activity Feed ── */
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }
        .dot-blue  { background: var(--accent); }
        .dot-green { background: var(--accent-3); }
        .dot-teal  { background: var(--accent-2); }
        .dot-warn  { background: var(--accent-warn); }

        .activity-text { font-size: 0.82rem; color: var(--text-primary); line-height: 1.4; }
        .activity-time { font-size: 0.7rem; color: var(--text-muted); margin-top: 3px; font-family: 'Space Mono', monospace; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card   { animation: fadeUp 0.4s ease both; }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.10s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.20s; }
    </style>
@endpush

@section('content')


    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card blue">
                <div class="stat-icon blue"><i class="fas fa-user-md"></i></div>
                <div>
                    <div class="stat-label">Total Doctors</div>
                    <div class="stat-value">{{ $totalDoctors }}</div>
                </div>
            </div>
        </div>

    </div>

@endsection
