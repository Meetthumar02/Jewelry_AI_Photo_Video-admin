@extends('layout.app')

@section('title', 'Edit Model Design - Creative AI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Edit Model Design</h3>
                        <p class="text-muted mb-0">Update model design information</p>
                    </div>
                    <a href="{{ route('admin.creative-ai.model-designs.index') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Please fix the following errors:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.creative-ai.model-designs.update', $modelDesign) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="industry_id" class="form-label fw-semibold">Industry <span class="text-danger">*</span></label>
                                    <select name="industry_id" id="industry_id" class="form-select @error('industry_id') is-invalid @enderror" required>
                                        <option value="">Select Industry</option>
                                        @foreach($industries as $industry)
                                            <option value="{{ $industry->id }}" {{ old('industry_id', $modelDesign->industry_id) == $industry->id ? 'selected' : '' }}>
                                                {{ $industry->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('industry_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $modelDesign->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="product_type_id" class="form-label fw-semibold">Product Type <span class="text-danger">*</span></label>
                                    <select name="product_type_id" id="product_type_id" class="form-select @error('product_type_id') is-invalid @enderror" required>
                                        <option value="">Select Product Type</option>
                                        @foreach($productTypes as $productType)
                                            <option value="{{ $productType->id }}" {{ old('product_type_id', $modelDesign->product_type_id) == $productType->id ? 'selected' : '' }}>
                                                {{ $productType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="shoot_type_id" class="form-label fw-semibold">Shoot Type <span class="text-danger">*</span></label>
                                    <select name="shoot_type_id" id="shoot_type_id" class="form-select @error('shoot_type_id') is-invalid @enderror" required>
                                        <option value="">Select Shoot Type</option>
                                        @foreach($shootTypes as $shootType)
                                            <option value="{{ $shootType->id }}" {{ old('shoot_type_id', $modelDesign->shoot_type_id) == $shootType->id ? 'selected' : '' }}>
                                                {{ $shootType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shoot_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Image</label>
                                @if ($modelDesign->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('upload/Model Design/' . $modelDesign->image) }}" 
                                            alt="Current Design" 
                                            class="img-thumbnail rounded" 
                                            style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                        <div class="mt-2">
                                            <small class="text-muted">Current image: {{ $modelDesign->image }}</small>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current image. Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF, WEBP</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                        {{ old('status', $modelDesign->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.creative-ai.model-designs.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-save me-2"></i> Update Model Design
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dependent Dropdowns for Edit Form
        const industrySelect = document.getElementById('industry_id');
        const categorySelect = document.getElementById('category_id');
        const productTypeSelect = document.getElementById('product_type_id');

        // Store original values for restoration
        const originalCategoryId = "{{ old('category_id', $modelDesign->category_id) }}";
        const originalProductTypeId = "{{ old('product_type_id', $modelDesign->product_type_id) }}";

        industrySelect.addEventListener('change', function() {
            const industryId = this.value;
            const currentCategoryId = categorySelect.value;
            
            categorySelect.innerHTML = '<option value="">Select Category</option>';
            productTypeSelect.innerHTML = '<option value="">Select Product Type</option>';
            
            if (industryId) {
                fetch(`{{ route('admin.creative-ai.get-categories-by-industry', ':id') }}`.replace(':id', industryId))
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            if (category.id == currentCategoryId || category.id == originalCategoryId) {
                                option.selected = true;
                            }
                            categorySelect.appendChild(option);
                        });
                    });
            }
        });

        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            const currentProductTypeId = productTypeSelect.value;
            
            productTypeSelect.innerHTML = '<option value="">Select Product Type</option>';
            
            if (categoryId) {
                fetch(`{{ route('admin.creative-ai.get-product-types-by-category', ':id') }}`.replace(':id', categoryId))
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(pt => {
                            const option = document.createElement('option');
                            option.value = pt.id;
                            option.textContent = pt.name;
                            if (pt.id == currentProductTypeId || pt.id == originalProductTypeId) {
                                option.selected = true;
                            }
                            productTypeSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
@endsection

