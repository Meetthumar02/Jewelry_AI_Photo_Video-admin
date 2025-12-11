@extends('layout.app')

@section('title', 'Edit Style - Creative AI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Edit Style Configuration</h3>
                        <p class="text-muted mb-0">Update defined combination</p>
                    </div>
                    <a href="{{ route('admin.creative-ai.styles.index') }}" class="btn btn-outline-secondary rounded-pill">
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

                        <form action="{{ route('admin.creative-ai.styles.update', $style) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="industry_id" class="form-label fw-semibold">Industry <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('industry_id') is-invalid @enderror" id="industry_id"
                                    name="industry_id" required>
                                    <option value="">-- Select Industry --</option>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}" {{ old('industry_id', $style->industry_id) == $industry->id ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industry_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-semibold">Category <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $style->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="product_type_id" class="form-label fw-semibold">Product Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('product_type_id') is-invalid @enderror"
                                    id="product_type_id" name="product_type_id" required>
                                    <option value="">-- Select Product Type --</option>
                                    @foreach($productTypes as $pt)
                                        <option value="{{ $pt->id }}" {{ old('product_type_id', $style->product_type_id) == $pt->id ? 'selected' : '' }}>
                                            {{ $pt->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.creative-ai.styles.index') }}"
                                    class="btn btn-outline-secondary rounded-pill">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-save me-2"></i> Update Configuration
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

            industrySelect.addEventListener('change', function () {
                loadCategories(this.value);
            });

            categorySelect.addEventListener('change', function () {
                loadProductTypes(this.value);
            });

            function loadCategories(industryId) {
                categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
                productTypeSelect.innerHTML = '<option value="">-- Select Product Type --</option>';
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
            }

            function loadProductTypes(categoryId) {
                productTypeSelect.innerHTML = '<option value="">-- Select Product Type --</option>';

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
            }
        });
    </script>
@endsection