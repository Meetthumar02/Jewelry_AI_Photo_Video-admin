@extends('layout.app')

@section('title', 'Model Designs - Creative AI')

@section('content')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">Model Designs</h3>
                <p class="text-muted mb-0">Manage design images for different styles and shoot types</p>
            </div>
            <div class="d-flex gap-2">
                <div class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fs-6 rounded-pill shadow-sm">
                    {{ $modelDesigns->total() }} Total Designs
                </div>
                <a href="{{ route('admin.creative-ai.model-designs.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus me-2"></i> Add New Design
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
                <form method="GET" action="{{ route('admin.creative-ai.model-designs.index') }}" class="row g-3 align-items-end">

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Industry</label>
                        <select name="industry_id" id="filter_industry_id" class="form-select shadow-sm">
                            <option value="">All Industries</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry->id }}" {{ request('industry_id') == $industry->id ? 'selected' : '' }}>
                                    {{ $industry->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Category</label>
                        <select name="category_id" id="filter_category_id" class="form-select shadow-sm" {{ request('industry_id') ? '' : 'disabled' }}>
                            <option value="">All Categories</option>
                            @if(request('industry_id') && isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Product Type</label>
                        <select name="product_type_id" id="filter_product_type_id" class="form-select shadow-sm" {{ request('category_id') ? '' : 'disabled' }}>
                            <option value="">All Types</option>
                            @if(request('category_id') && isset($productTypes))
                                @foreach($productTypes as $pt)
                                    <option value="{{ $pt->id }}" {{ request('product_type_id') == $pt->id ? 'selected' : '' }}>
                                        {{ $pt->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Shoot Type</label>
                        <select name="shoot_type_id" class="form-select shadow-sm">
                            <option value="">All Shoot Types</option>
                            @foreach($shootTypes as $shootType)
                                <option value="{{ $shootType->id }}" {{ request('shoot_type_id') == $shootType->id ? 'selected' : '' }}>
                                    {{ $shootType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select shadow-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill">Apply</button>
                    </div>

                    @if (request()->hasAny(['industry_id', 'category_id', 'product_type_id', 'shoot_type_id', 'status']))
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.creative-ai.model-designs.index') }}"
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
                                <th style="width: 100px;">Image</th>
                                <th>Industry</th>
                                <th>Category</th>
                                <th>Product Type</th>
                                <th>Shoot Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($modelDesigns as $design)
                                <tr class="border-bottom table-row-hover">

                                    <td>
                                        @if($design->image)
                                            <img src="{{ asset('upload/Model Design/' . $design->image) }}" 
                                                 alt="Design" 
                                                 class="img-thumbnail rounded" 
                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 onclick="showImageModal('{{ asset('upload/Model Design/' . $design->image) }}')">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $design->industry->name ?? 'N/A' }}</div>
                                    </td>

                                    <td>
                                        <div class="text-muted">{{ $design->category->name ?? 'N/A' }}</div>
                                    </td>
                                    
                                    <td>
                                        <div class="text-muted">{{ $design->productType->name ?? 'N/A' }}</div>
                                    </td>

                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                            {{ $design->shootType->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if ($design->status)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">

                                            <a href="{{ route('admin.creative-ai.model-designs.edit', $design) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.creative-ai.model-designs.destroy', $design) }}"
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
                                    <td colspan="7" class="text-center py-5 text-muted">No model designs found</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            @if ($modelDesigns->hasPages())
                <div class="card-footer bg-white border-top py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="small text-muted">
                            Showing {{ $modelDesigns->firstItem() }} to {{ $modelDesigns->lastItem() }} of {{ $modelDesigns->total() }}
                            designs
                        </div>
                        {{ $modelDesigns->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>

    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Design" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-row-hover:hover {
            background: #f8f9fd;
        }
    </style>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Auto-hide success messages
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
                    text: "This will permanently delete the design and its image.",
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
        
        // Dynamic Filters
        const industrySelect = document.getElementById('filter_industry_id');
        const categorySelect = document.getElementById('filter_category_id');
        const productTypeSelect = document.getElementById('filter_product_type_id');
        
        industrySelect.addEventListener('change', function() {
            const industryId = this.value;
            categorySelect.innerHTML = '<option value="">All Categories</option>';
            productTypeSelect.innerHTML = '<option value="">All Types</option>';
            productTypeSelect.disabled = true;
            
            if (industryId) {
                categorySelect.disabled = false;
                fetch(`{{ route('admin.creative-ai.get-categories-by-industry', ':id') }}`.replace(':id', industryId))
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });
                    });
            } else {
                categorySelect.disabled = true;
            }
        });
        
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            productTypeSelect.innerHTML = '<option value="">All Types</option>';
            
            if (categoryId) {
                productTypeSelect.disabled = false;
                fetch(`{{ route('admin.creative-ai.get-product-types-by-category', ':id') }}`.replace(':id', categoryId))
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(pt => {
                            const option = document.createElement('option');
                            option.value = pt.id;
                            option.textContent = pt.name;
                            productTypeSelect.appendChild(option);
                        });
                    });
            } else {
                productTypeSelect.disabled = true;
            }
        });

        // Image Modal
        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>

@endsection
