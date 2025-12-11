@extends('layout.app')

@section('title', 'Create Style Wizard - Creative AI')@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Create New Style Configuration</h3>
                <p class="text-muted mb-0">Follow the steps to build your hierarchy</p>
            </div>
            <a href="{{ route('admin.creative-ai.styles.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>

        <div class="row g-4">
            {{-- BOX 1: Industry --}}
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-primary bg-opacity-10 py-3">
                        <h5 class="fw-bold text-primary mb-0"><i class="fas fa-industry me-2"></i> Step 1: Industry</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Add a new Industry unless it already exists.</p>

                        <form id="form_industry" onsubmit="submitIndustry(event)">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Industry Name</label>
                                <input type="text" class="form-control" name="name" required placeholder="e.g. Jewelry">
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                <i class="fas fa-plus me-2"></i> Add Industry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOX 2: Category --}}
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-info bg-opacity-10 py-3">
                        <h5 class="fw-bold text-info mb-0"><i class="fas fa-layer-group me-2"></i> Step 2: Category</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Select Industry, then add a Category.</p>

                        <form id="form_category" onsubmit="submitCategory(event)">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Industry</label>
                                <select class="form-select industry-select" name="industry_id" required onchange="refreshCategories(this.value)">
                                    <option value="">-- Choose Industry --</option>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category Name</label>
                                <input type="text" class="form-control" name="name" required placeholder="e.g. Rings">
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-white w-100 rounded-pill">
                                <i class="fas fa-plus me-2"></i> Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOX 3: Product Type --}}
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-success bg-opacity-10 py-3">
                        <h5 class="fw-bold text-success mb-0"><i class="fas fa-tag me-2"></i> Step 3: Product Type</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Select Category, add Product Type. This completes the configuration.</p>

                        <form id="form_product_type" onsubmit="submitProductType(event)">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Industry</label>
                                <select class="form-select industry-select" id="box3_industry" required onchange="loadCategoriesForBox3(this.value)">
                                    <option value="">-- Choose Industry --</option>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Category</label>
                                <select class="form-select" name="category_id" id="box3_category" required disabled>
                                    <option value="">-- Choose Category --</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Product Type Name</label>
                                <input type="text" class="form-control" name="name" required placeholder="e.g. Engagement Ring">
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success text-white w-100 rounded-pill">
                                <i class="fas fa-check me-2"></i> Save Configuration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';

        // --- Box 1: Industry ---
        function submitIndustry(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('{{ route('admin.creative-ai.industries.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Success', 'Industry added!', 'success');
                    form.reset();
                    // Reload all industry dropdowns
                    reloadIndustries();
                } else {
                    Swal.fire('Error', 'Failed to add Industry', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
        }

        // --- Box 2: Category ---
        function submitCategory(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('{{ route('admin.creative-ai.categories.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Success', 'Category added!', 'success');
                    form.reset();
                    // Refresh Category dropdown in Box 3 if industry matches
                    const box3Industry = document.getElementById('box3_industry').value;
                    if(box3Industry) loadCategoriesForBox3(box3Industry);
                } else {
                    Swal.fire('Error', 'Failed to add Category', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
        }

        // --- Box 3: Product Type ---
        function submitProductType(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('{{ route('admin.creative-ai.product-types.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Configuration Saved!',
                        text: 'Style configuration created successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to List'
                    }).then((result) => {
                        if (result.isConfirmed) {
                             window.location.href = '{{ route('admin.creative-ai.styles.index') }}';
                        } else {
                             form.reset();
                             document.getElementById('box3_category').innerHTML = '<option value="">-- Choose Category --</option>';
                             document.getElementById('box3_category').disabled = true;
                        }
                    });
                } else {
                    Swal.fire('Error', 'Failed to add Product Type', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Something went wrong', 'error'));
        }

        // --- Helpers ---

        function reloadIndustries() {
            // Fetch latest industries list or just reload page?
            // For simplicity in this specialized UI, reloading the page might be jarring.
            // Let's implement a simple fetch to get industry list if we had an API for it.
            // Since we don't have a pure "get all industries" JSON API, we can just reload the page for now
            // OR we can manually append the new industry if we returned it in JSON.
            // For now, let's just reload strictly to be safe, or ask user?
            // Better: just reload.
            location.reload(); 
        }

        function loadCategoriesForBox3(industryId) {
            const catSelect = document.getElementById('box3_category');
            catSelect.innerHTML = '<option value="">-- Choose Category --</option>';

            if(!industryId) {
                catSelect.disabled = true;
                return;
            }

            catSelect.disabled = false;
            fetch(`{{ route('admin.creative-ai.get-categories-by-industry', ':id') }}`.replace(':id', industryId))
                .then(res => res.json())
                .then(data => {
                    data.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        catSelect.appendChild(opt);
                    });
                });
        }

        // Allow Box 2 to refresh its own category list? No, Box 2 adds categories.
        // But Box 2 category addition might depend on an Industry select.
        function refreshCategories(industryId) {
             // This function is placeholder if we needed to show existing categories in Box 2
        }

    </script>
@endsection
