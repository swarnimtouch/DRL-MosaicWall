@extends('layouts.admin')

@section('title', 'Doctors')
@section('page-title', 'Doctors')

@push('styles')
    <style>
        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .page-title-group h4 { font-size: 1.3rem; font-weight: 700; margin-bottom: 3px; }
        .page-title-group p  { font-size: 0.82rem; color: var(--text-muted); }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--accent), #224abe);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.855rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn-add:hover { opacity: 0.88; color: #fff; transform: translateY(-1px); }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            padding: 14px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 18px;
        }
        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }
        .search-wrap i {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
        }
        .filter-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.855rem;
            padding: 8px 12px 8px 34px;
            width: 100%;
            outline: none;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .filter-input:focus { border-color: var(--accent); background: rgba(255,255,255,0.09); }
        .filter-input::placeholder { color: var(--text-muted); }

        .filter-select {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.855rem;
            padding: 8px 12px;
            outline: none;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: var(--accent); }
        .filter-select option { background: #1a1f33; }

        /* ── Table ── */
        .doctors-table-wrap { overflow-x: auto; border-radius: 14px; }
        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.855rem;
        }
        .doctors-table thead tr {
            background: rgba(255,255,255,0.03);
        }
        .doctors-table th {
            padding: 13px 16px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .doctors-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
            color: var(--text-primary);
        }
        .doctors-table tbody tr:last-child td { border-bottom: none; }
        .doctors-table tbody tr { transition: background 0.15s; }
        .doctors-table tbody tr:hover td { background: var(--bg-card-h); }

        /* Doctor Name Cell */
        .doc-name-cell { display: flex; align-items: center; gap: 12px; }
        .doc-av {
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .doc-av.c1 { background: linear-gradient(135deg, #4e73df, #224abe); }
        .doc-av.c2 { background: linear-gradient(135deg, #36b9cc, #1a8a9a); }
        .doc-av.c3 { background: linear-gradient(135deg, #1cc88a, #149968); }
        .doc-av.c4 { background: linear-gradient(135deg, #f6c23e, #d4a017); }
        .doc-av.c5 { background: linear-gradient(135deg, #e74a3b, #b53029); }

        .doc-name  { font-weight: 600; color: var(--text-primary); }
        .doc-email { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

        /* Badges */
        .badge-status {
            font-size: 0.68rem; font-weight: 600;
            padding: 3px 9px; border-radius: 20px;
            letter-spacing: 0.04em; white-space: nowrap;
        }
        .badge-active   { background: rgba(28,200,138,0.15); color: #1cc88a; border: 1px solid rgba(28,200,138,0.2); }
        .badge-inactive { background: rgba(231,74,59,0.12);  color: #e74a3b; border: 1px solid rgba(231,74,59,0.2); }
        .badge-spec {
            font-size: 0.68rem; font-weight: 600;
            padding: 3px 9px; border-radius: 20px;
            background: rgba(78,115,223,0.12);
            color: #7b9ff5;
            border: 1px solid rgba(78,115,223,0.2);
        }

        /* Action Buttons */
        .action-btns { display: flex; gap: 6px; align-items: center; }
        .act-btn {
            width: 30px; height: 30px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
            background: none;
        }
        .act-btn.edit  { color: #36b9cc; border-color: rgba(54,185,204,0.2); background: rgba(54,185,204,0.08); }
        .act-btn.edit:hover  { background: rgba(54,185,204,0.2); border-color: rgba(54,185,204,0.4); }
        .act-btn.del   { color: #e74a3b; border-color: rgba(231,74,59,0.2);  background: rgba(231,74,59,0.08); }
        .act-btn.del:hover   { background: rgba(231,74,59,0.2);  border-color: rgba(231,74,59,0.4); }
        .act-btn.view  { color: #7b9ff5; border-color: rgba(123,159,245,0.2); background: rgba(123,159,245,0.08); }
        .act-btn.view:hover  { background: rgba(123,159,245,0.2); }

        /* Pagination */
        .pagination-wrap { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 14px 18px; border-top: 1px solid var(--border); }
        .page-info { font-size: 0.78rem; color: var(--text-muted); }
        .custom-pagination { display: flex; gap: 4px; }
        .page-btn {
            width: 32px; height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .page-btn:hover, .page-btn.active { background: var(--accent); border-color: var(--accent); color: #fff; }

        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 3.5rem; color: var(--text-muted); opacity: 0.3; margin-bottom: 16px; }
        .empty-state h5 { font-size: 1rem; color: var(--text-muted); margin-bottom: 8px; }
        .empty-state p { font-size: 0.82rem; color: var(--text-muted); opacity: 0.7; }

        /* Mobile card view */
        @media (max-width: 640px) {
            .hide-mobile { display: none; }
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-title-group">
            <h4>Doctors</h4>
            <p>Manage all registered doctors in the system</p>
        </div>

    </div>

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-3"
             style="background:rgba(28,200,138,0.1); border:1px solid rgba(28,200,138,0.25); border-radius:10px; color:#1cc88a; font-size:0.875rem; padding:12px 16px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ── Filter Bar ── --}}
    <form method="GET" action="{{ route('admin.doctors.index') }}">
        <div class="filter-bar">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="filter-input" placeholder="Search by name, email, specialty...">
            </div>

            <select name="specialty" class="filter-select">
                <option value="">All Specialties</option>
                @foreach($specialties ?? [] as $spec)
                    <option value="{{ $spec }}" {{ request('specialty') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                @endforeach
            </select>

            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="btn-add" style="padding:8px 16px;">
                <i class="fas fa-filter"></i>
                <span class="d-none d-sm-inline">Filter</span>
            </button>
        </div>
    </form>

    {{-- ── Main Table Card ── --}}
    <div class="glass-card">
        <div class="doctors-table-wrap">
            <table class="doctors-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="hide-mobile">Emp Id</th>
                    <th class="hide-mobile">HQ</th>
                    <th class="hide-mobile">Photo</th>
                    <th class="d-none d-lg-table-cell">Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($doctors as $index => $doctor)
                    @php $colors = ['c1','c2','c3','c4','c5']; $c = $colors[$index % 5]; @endphp
                    <tr>
                        <td style="color:var(--text-muted); font-family:'Space Mono',monospace; font-size:0.75rem;">
                            {{ $index + 1}}
                        </td>
                        <td>
                            <div class="doc-name-cell">
                                <div>
                                    <div class="doc-name">{{ $doctor->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile">
                                <span class="badge-spec">{{ $doctor->emp_id }}</span>

                        </td>
                        <td class="hide-mobile" style="color:var(--text-muted); font-family:'Space Mono',monospace; font-size:0.78rem;">
                            {{ $doctor->hq ?? '—' }}
                        </td>
                        <td class="hide-mobile" style="color:var(--text-muted); font-family:'Space Mono',monospace; font-size:0.78rem;">
                            @if($doctor->photo)
                                <img src="{{ $doctor->photo }}"
                                     style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif

                        </td>
                        <td class="d-none d-lg-table-cell" style="color:var(--text-muted); font-size:0.78rem;">
                            {{ $doctor->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <div class="action-btns">

                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                      onsubmit="return confirm('Delete Dr. {{ $doctor->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-btn del" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-user-md d-block"></i>
                                <h5>No doctors found</h5>
                                <p>Try adjusting your search or <a href="{{ route('admin.doctors.create') }}" style="color:var(--accent);">add a new doctor</a>.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($doctors->hasPages())
            <div class="pagination-wrap">
                <div class="page-info">
                    Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }} doctors
                </div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4; cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

@endsection
