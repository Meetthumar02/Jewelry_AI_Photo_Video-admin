@extends('layout.app')

@section('title', 'Edit Style - Creative AI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Edit Style</h3>
                        <p class="text-muted mb-0">Update style information</p>
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

                        <form action="{{ route('admin.creative-ai.styles.update', $style) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">Style Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $style->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4">{{ old('description', $style->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Image</label>
                                @if ($style->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $style->image) }}" alt="{{ $style->name }}" 
                                            style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                                        <div class="mt-2">
                                            <small class="text-muted">Current image</small>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                    id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current image. Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                        id="sort_order" name="sort_order" value="{{ old('sort_order', $style->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold d-block">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                            {{ old('status', $style->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.creative-ai.styles.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-save me-2"></i> Update Style
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

