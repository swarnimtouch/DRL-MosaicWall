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
            white-space: nowrap;
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
            min-width: 160px;
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

        /* ── Desktop Table ── */
        .doctors-table-wrap { overflow-x: auto; border-radius: 14px; }
        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.855rem;
        }
        .doctors-table thead tr { background: rgba(255,255,255,0.03); }
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

        .doc-name { font-weight: 600; color: var(--text-primary); }

        .badge-spec {
            font-size: 0.72rem; font-weight: 700;
            padding: 4px 10px; border-radius: 20px;
            background: rgba(78,115,223,0.12);
            color: #7b9ff5;
            border: 1px solid rgba(78,115,223,0.2);
            font-family: 'Space Mono', monospace;
        }

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
        .act-btn.del { color: #e74a3b; border-color: rgba(231,74,59,0.2); background: rgba(231,74,59,0.08); }
        .act-btn.del:hover { background: rgba(231,74,59,0.2); border-color: rgba(231,74,59,0.4); }

        .photo-thumb {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .photo-thumb:hover {
            transform: scale(1.12);
            box-shadow: 0 0 0 3px var(--accent);
        }

        /* Pagination */
        .pagination-wrap {
            display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap;
            gap: 10px; padding: 14px 18px;
            border-top: 1px solid var(--border);
        }
        .page-info { font-size: 0.78rem; color: var(--text-muted); }
        .custom-pagination { display: flex; gap: 4px; flex-wrap: wrap; }
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

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 3.5rem; color: var(--text-muted); opacity: 0.3; margin-bottom: 16px; }
        .empty-state h5 { font-size: 1rem; color: var(--text-muted); margin-bottom: 8px; }
        .empty-state p  { font-size: 0.82rem; color: var(--text-muted); opacity: 0.7; }

        /* ══════════════════════════════════════
           RESPONSIVE: Desktop vs Mobile
        ══════════════════════════════════════ */
        .desktop-table { display: block; }
        .mobile-cards  { display: none; }

        @media (max-width: 767px) {
            .desktop-table { display: none !important; }
            .mobile-cards  { display: block; }
            .filter-bar { padding: 12px; }
        }

        /* ── Mobile Doctor Card ── */
        .doc-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            transition: border-color 0.2s;
            animation: fadeUp 0.3s ease both;
        }
        .doc-card:hover { border-color: var(--border-h); }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .doc-card-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .doc-card-photo {
            width: 56px; height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            flex-shrink: 0;
            cursor: pointer;
            transition: border-color 0.2s, transform 0.2s;
        }
        .doc-card-photo:hover { border-color: var(--accent); transform: scale(1.06); }

        .doc-card-av {
            width: 56px; height: 56px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .doc-card-info { flex: 1; min-width: 0; }
        .doc-card-name {
            font-size: 1rem; font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .doc-card-serial {
            font-size: 0.7rem; color: var(--text-muted);
            font-family: 'Space Mono', monospace; margin-top: 2px;
        }

        /* 2-column fields grid */
        .doc-card-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 14px;
        }
        .doc-field {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 10px;
        }
        .doc-field-label {
            font-size: 0.6rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--text-muted); margin-bottom: 4px;
        }
        .doc-field-value {
            font-size: 0.82rem; font-weight: 600;
            color: var(--text-primary); word-break: break-word;
        }
        .doc-field-value.mono { font-family: 'Space Mono', monospace; font-size: 0.75rem; color: #7b9ff5; }
        .doc-field-value.muted { color: var(--text-muted); font-weight: 400; }

        .doc-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            gap: 8px;
        }
        .doc-card-date {
            font-size: 0.72rem; color: var(--text-muted);
            font-family: 'Space Mono', monospace;
        }
        .btn-del-mobile {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px;
            background: rgba(231,74,59,0.08);
            border: 1px solid rgba(231,74,59,0.25);
            border-radius: 8px;
            color: #e74a3b;
            font-size: 0.78rem; font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-del-mobile:hover { background: rgba(231,74,59,0.2); }

        /* ── Photo Modal ── */
        .photo-modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.82);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .photo-modal-overlay.open { display: flex; }

        .photo-modal-box {
            position: relative;
            background: rgba(15,20,38,0.98);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 18px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            animation: popIn 0.28s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes popIn {
            from { opacity:0; transform:scale(0.82); }
            to   { opacity:1; transform:scale(1); }
        }
        .photo-modal-box img {
            width: 100%; border-radius: 12px;
            object-fit: cover; display: block; max-height: 420px;
        }
        .photo-modal-name {
            text-align: center; margin-top: 14px;
            font-size: 1rem; font-weight: 700; color: var(--text-primary);
        }
        .photo-modal-empid {
            text-align: center; font-size: 0.75rem;
            color: var(--text-muted); font-family: 'Space Mono', monospace; margin-top: 4px;
        }
        .photo-modal-close {
            position: absolute; top: -13px; right: -13px;
            width: 32px; height: 32px; border-radius: 50%;
            background: #e74a3b; border: 2px solid var(--bg-deep);
            color: #fff; font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
            transition: transform 0.2s; z-index: 10;
        }
        .photo-modal-close:hover { transform: scale(1.15); }
        .modal-no-photo {
            width: 100%; height: 180px; border-radius: 12px;
            background: rgba(255,255,255,0.03);
            border: 2px dashed var(--border);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: var(--text-muted); gap: 10px;
        }
        .modal-no-photo i { font-size: 2.5rem; opacity: 0.25; }
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
                       class="filter-input" placeholder="Search by name or Emp ID...">
            </div>
            <button type="submit" class="btn-add" style="padding:8px 18px;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    {{-- ══════════════════════════════════
         DESKTOP TABLE  (≥ 768px)
    ══════════════════════════════════ --}}
    <div class="glass-card desktop-table">
        <div class="doctors-table-wrap">
            <table class="doctors-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Emp ID</th>
                    <th>Headquarters</th>
                    <th>Photo</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($doctors as $index => $doctor)
                    @php $colors = ['c1','c2','c3','c4','c5']; $c = $colors[$index % 5]; @endphp
                    <tr>
                        <td style="color:var(--text-muted); font-family:'Space Mono',monospace; font-size:0.75rem;">
                            {{ $doctors->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="doc-name-cell">
                                <div class="doc-name">{{ $doctor->name }}</div>
                            </div>
                        </td>
                        <td><span class="badge-spec">{{ $doctor->emp_id }}</span></td>
                        <td style="color:var(--text-muted); font-size:0.82rem;">{{ $doctor->hq ?? '—' }}</td>
                        <td>
                            @if($doctor->photo)
                                <img src="{{  $doctor->photo }}"
                                     class="photo-thumb"
                                     style="width:42px; height:42px; border-radius:50%; object-fit:cover;"
                                     onclick="openPhotoModal('{{  $doctor->photo }}', '{{ $doctor->name }}', '{{ $doctor->emp_id }}')"
                                     alt="{{ $doctor->name }}">
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted); font-size:0.78rem;">
                            {{ $doctor->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="action-btns">
                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $doctor->name }}?')">
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
                                <h5>No records found</h5>
                                <p>No doctors have been added yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div class="pagination-wrap">
                <div class="page-info">Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}</div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════
         MOBILE CARDS  (< 768px)
         Har field card mein clearly dikhegi
    ══════════════════════════════════ --}}
    <div class="mobile-cards">
        @forelse($doctors as $index => $doctor)
            @php $colors = ['c1','c2','c3','c4','c5']; $c = $colors[$index % 5]; @endphp

            <div class="doc-card" style="animation-delay:{{ $index * 0.05 }}s">

                {{-- Top Row: Photo/Avatar + Name + Serial --}}
                <div class="doc-card-top">
                    @if($doctor->photo)
                        <img src="{{  $doctor->photo }}"
                             class="doc-card-photo"
                             onclick="openPhotoModal('{{  $doctor->photo }}', '{{ $doctor->name }}', '{{ $doctor->emp_id }}')"
                             alt="{{ $doctor->name }}">
                    @else
                        <div class="doc-card-av {{ $c }}">{{ strtoupper(substr($doctor->name, 0, 1)) }}</div>
                    @endif
                    <div class="doc-card-info">
                        <div class="doc-card-name">{{ $doctor->name }}</div>
                        <div class="doc-card-serial">Record #{{ $doctors->firstItem() + $index }}</div>
                    </div>
                </div>

                {{-- Fields: 2-column grid — sab visible --}}
                <div class="doc-card-fields">
                    <div class="doc-field">
                        <div class="doc-field-label"><i class="fas fa-id-badge me-1"></i>Emp ID</div>
                        <div class="doc-field-value mono">{{ $doctor->emp_id }}</div>
                    </div>
                    <div class="doc-field">
                        <div class="doc-field-label"><i class="fas fa-building me-1"></i>Headquarters</div>
                        <div class="doc-field-value {{ $doctor->hq ? '' : 'muted' }}">{{ $doctor->hq ?? 'Not set' }}</div>
                    </div>
                    <div class="doc-field" style="grid-column: span 2;">
                        <div class="doc-field-label"><i class="fas fa-calendar me-1"></i>Created At</div>
                        <div class="doc-field-value mono" style="color:var(--text-muted); font-weight:400;">
                            {{ $doctor->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                {{-- Footer: Delete button --}}
                <div class="doc-card-footer">
                    <div class="doc-card-date">
                        @if($doctor->photo)
                            <span style="color:var(--accent-3); font-size:0.72rem;">
                        <i class="fas fa-image me-1"></i> Photo uploaded
                    </span>
                        @else
                            <span style="color:var(--text-muted); font-size:0.72rem;">
                        <i class="fas fa-image me-1"></i> No photo
                    </span>
                        @endif
                    </div>
                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                          onsubmit="return confirm('Delete {{ $doctor->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del-mobile">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

        @empty
            <div class="glass-card">
                <div class="empty-state">
                    <i class="fas fa-user-md d-block"></i>
                    <h5>No records found</h5>
                    <p>No doctors have been added yet.</p>
                </div>
            </div>
        @endforelse

        {{-- Mobile Pagination --}}
        @if($doctors->hasPages())
            <div class="pagination-wrap" style="border:none; padding:4px 0 12px;">
                <div class="page-info">{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}</div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ── Photo Preview Modal ── --}}
    <div class="photo-modal-overlay" id="photoModal" onclick="closePhotoModal(event)">
        <div class="photo-modal-box">
            <button class="photo-modal-close"
                    onclick="document.getElementById('photoModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
            <div id="modalImgWrap"></div>
            <div class="photo-modal-name"  id="modalName"></div>
            <div class="photo-modal-empid" id="modalEmpId"></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openPhotoModal(src, name, empId) {
            const wrap = document.getElementById('modalImgWrap');
            if (src && src !== '' && src !== 'null') {
                wrap.innerHTML = `<img src="${src}" alt="${name}">`;
            } else {
                wrap.innerHTML = `
                <div style="width:100%;height:180px;border-radius:12px;background:rgba(255,255,255,0.03);border:2px dashed rgba(255,255,255,0.08);display:flex;flex-direction:column;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);gap:10px;">
                    <i class="fas fa-user-md" style="font-size:2.5rem;opacity:0.25;"></i>
                    <span style="font-size:0.82rem;">No photo uploaded</span>
                </div>`;
            }
            document.getElementById('modalName').textContent  = name  || '';
            document.getElementById('modalEmpId').textContent = empId ? 'EMP-ID: ' + empId : '';
            document.getElementById('photoModal').classList.add('open');
        }

        function closePhotoModal(e) {
            if (e.target.id === 'photoModal') {
                document.getElementById('photoModal').classList.remove('open');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('photoModal').classList.remove('open');
            }
        });
    </script>
@endpush
