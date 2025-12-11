@extends('layout.app')

@section('title', 'Create Model Design - Creative AI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Create New Model Design</h3>
                        <p class="text-muted mb-0">Upload design image for specific style and shoot type</p>
                    </div>
                    <a href="{{ route('admin.creative-ai.model-designs.index') }}"
                        class="btn btn-outline-secondary rounded-pill">
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

                        <form action="{{ route('admin.creative-ai.model-designs.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4">
                                {{-- Industry Selection --}}
                                <div class="col-md-6">
                                    <label for="industry_id" class="form-label fw-semibold">1. Select Industry <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('industry_id') is-invalid @enderror" id="industry_id"
                                        name="industry_id" required>
                                        <option value="">-- Choose Industry --</option>
                                        @foreach($industries as $industry)
                                            <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
                                                {{ $industry->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('industry_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Category Selection --}}
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label fw-semibold">2. Select Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required disabled>
                                        <option value="">-- Choose Category --</option>
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Product Type Selection --}}
                                <div class="col-md-6">
                                    <label for="product_type_id" class="form-label fw-semibold">3. Select Product Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('product_type_id') is-invalid @enderror"
                                        id="product_type_id" name="product_type_id" required disabled>
                                        <option value="">-- Choose Product Type --</option>
                                    </select>
                                    @error('product_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Shoot Type Selection --}}
                                <div class="col-md-6">
                                    <label for="shoot_type_id" class="form-label fw-semibold">4. Select Shoot Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('shoot_type_id') is-invalid @enderror"
                                        id="shoot_type_id" name="shoot_type_id" required>
                                        <option value="">-- Choose Shoot Type --</option>
                                        @foreach($shootTypes as $shootType)
                                            <option value="{{ $shootType->id }}" {{ old('shoot_type_id') == $shootType->id ? 'selected' : '' }}>
                                                {{ $shootType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shoot_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image Upload --}}
                                <div class="col-12">
                                    <label for="image" class="form-label fw-semibold">5. Upload Design Image <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                        name="image" accept="image/*" required>
                                    <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB)</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Image Preview --}}
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                            style="max-height: 200px;">
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold d-block">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                            {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="{{ route('admin.creative-ai.model-designs.index') }}"
                                    class="btn btn-outline-secondary rounded-pill">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-save me-2"></i> Create Model Design
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const industrySelect = document.getElementById('industry_id');
            const categorySelect = document.getElementById('category_id');
            const productTypeSelect = document.getElementById('product_type_id');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            // Check for retained values on error/reload
            if (industrySelect.value) {
                loadCategories(industrySelect.value, '{{ old('category_id') }}');
            }
            if ('{{ old('category_id') }}') {
                loadProductTypes('{{ old('category_id') }}', '{{ old('product_type_id') }}');
            }

            industrySelect.addEventListener('change', function () {
                loadCategories(this.value);
            });

            categorySelect.addEventListener('change', function () {
                loadProductTypes(this.value);
            });

            // Image Preview
            imageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            function loadCategories(industryId, selectedCategory = null) {
                categorySelect.innerHTML = '<option value="">-- Choose Category --</option>';
                productTypeSelect.innerHTML = '<option value="">-- Choose Product Type --</option>';
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
                                if (selectedCategory && category.id == selectedCategory) {
                                    option.selected = true;
                                }
                                categorySelect.appendChild(option);
                            });
                        });
                } else {
                    categorySelect.disabled = true;
                }
            }

            function loadProductTypes(categoryId, selectedProductType = null) {
                productTypeSelect.innerHTML = '<option value="">-- Choose Product Type --</option>';

                if (categoryId) {
                    productTypeSelect.disabled = false;
                    fetch(`{{ route('admin.creative-ai.get-product-types-by-category', ':id') }}`.replace(':id', categoryId))
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(pt => {
                                const option = document.createElement('option');
                                option.value = pt.id;
                                option.textContent = pt.name;
                                if (selectedProductType && pt.id == selectedProductType) {
                                    option.selected = true;
                                }
                                productTypeSelect.appendChild(option);
                            });
                        });
                } else {
                    productTypeSelect.disabled = true;
                }
            }
        });
    </script>
@endsection