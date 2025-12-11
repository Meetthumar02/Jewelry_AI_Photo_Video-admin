@extends('layout.app')

@section('title', 'Industries - Creative AI')

@section('content')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">Industries</h3>
                <p class="text-muted mb-0">Manage industries for Creative AI</p>
            </div>
            <div class="d-flex gap-2">
                <div class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fs-6 rounded-pill shadow-sm">
                    {{ $industries->total() }} Total Industries
                </div>
                <a href="{{ route('admin.creative-ai.industries.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i> Add New Industry
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
                <form method="GET" action="{{ route('admin.creative-ai.industries.index') }}"
                    class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <label class="form-label small text-muted">Search Industries</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0" placeholder="Search by name..."
                                value="{{ request('search') }}">
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

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill">Apply</button>
                    </div>

                    @if (request('search') || request('status'))
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.creative-ai.industries.index') }}"
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
                                <th>Name</th>
                                <th class="text-center">Status</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($industries as $industry)
                                <tr class="border-bottom table-row-hover">

                                    <td>
                                        <div class="fw-semibold">{{ $industry->name }}</div>
                                    </td>

                                    <td class="text-center">
                                        @if ($industry->status)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="text-muted small">
                                            {{ $industry->created_at->format('M d, Y') }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">

                                            <a href="{{ route('admin.creative-ai.industries.edit', $industry) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.creative-ai.industries.destroy', $industry) }}"
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
                                    <td colspan="4" class="text-center py-5 text-muted">No industries found</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            @if ($industries->hasPages())
                <div class="card-footer bg-white border-top py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="small text-muted">
                            Showing {{ $industries->firstItem() }} to {{ $industries->lastItem() }} of
                            {{ $industries->total() }}
                            industries
                        </div>
                        {{ $industries->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>

    </div>

    <style>
        .table-row-hover:hover {
            background: #f8f9fd;
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
            form.addEventListener('submit', function (e) {
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