@extends('layout.app')

@section('title', 'Select Your Style - Creative AI')

@section('content')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">Select Your Style</h3>
                <p class="text-muted mb-0">Manage styles for Creative AI</p>
            </div>
            <div class="d-flex gap-2">
                <div class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fs-6 rounded-pill shadow-sm">
                    {{ $styles->total() }} Total Styles
                </div>
                <a href="{{ route('admin.creative-ai.styles.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i> Add New Style
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <div class="card-header bg-white border-bottom py-4">
                <form method="GET" action="{{ route('admin.creative-ai.styles.index') }}" class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <label class="form-label small text-muted">Search Styles</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0"
                                placeholder="Search by name or description..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select shadow-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Only
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Sort By</label>
                        <select name="sort" class="form-select shadow-sm">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A
                            </option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill">Apply</button>
                    </div>

                    @if (request('search') || request('status') || request('sort'))
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.creative-ai.styles.index') }}"
                                class="btn btn-outline-secondary btn-sm rounded-pill">
                                Clear All Filters
                            </a>
                        </div>
                    @endif

                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-muted text-uppercase small">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Sort Order</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($styles as $style)
                                <tr class="border-bottom table-row-hover">

                                    <td>
                                        @if ($style->image && $style->image_url)
                                            <img src="{{ $style->image_url }}" alt="{{ $style->name }}"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px; border-radius: 8px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $style->name }}</div>
                                    </td>

                                    <td>
                                        <div class="text-muted small">
                                            {{ Str::limit($style->description ?? 'No description', 50) }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if ($style->status)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                            {{ $style->sort_order }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="text-muted small">
                                            {{ $style->created_at->format('M d, Y') }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">

                                            <a href="{{ route('admin.creative-ai.styles.edit', $style) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.creative-ai.styles.destroy', $style) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No styles found</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            @if ($styles->hasPages())
                <div class="card-footer bg-white border-top py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="small text-muted">
                            Showing {{ $styles->firstItem() }} to {{ $styles->lastItem() }} of {{ $styles->total() }}
                            styles
                        </div>

                        <nav>
                            <ul class="pagination pagination-custom mb-0">
                                @if ($styles->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $styles->previousPageUrl() }}">Prev</a></li>
                                @endif

                                @foreach ($styles->getUrlRange(1, $styles->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $styles->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($styles->hasMorePages())
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $styles->nextPageUrl() }}">Next</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                @endif
                            </ul>
                        </nav>

                    </div>
                </div>
            @endif

        </div>

    </div>


    <style>
        .table-row-hover:hover {
            background: #f8f9fd;
        }

        .pagination-custom .page-link {
            border-radius: 10px;
            border: none;
            margin: 0 4px;
            color: #667eea;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pagination-custom .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .pagination-custom .page-link:hover {
            background: #667eea;
            color: #fff;
        }
    </style>


    {{-- SweetAlert + Auto Hide --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Auto-hide success messages after 5 seconds
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);

        // SweetAlert delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#e3342f",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

            });
        });
    </script>

@endsection
