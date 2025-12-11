@extends('layout.app')

@section('title', 'Style Details - Creative AI')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Style Configuration Details</h3>
                        <p class="text-muted mb-0">View configuration details</p>
                    </div>
                    <a href="{{ route('admin.creative-ai.styles.index') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <h5 class="fw-bold text-primary mb-3">Configuration</h5>
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0" style="width: 150px;">Industry:</th>
                                            <td>{{ $style->industry->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Category:</th>
                                            <td>{{ $style->category->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Product Type:</th>
                                            <td>{{ $style->productType->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Created At:</th>
                                            <td>{{ $style->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Last Updated:</th>
                                            <td>{{ $style->updated_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.creative-ai.styles.edit', $style) }}"
                                class="btn btn-primary rounded-pill">
                                <i class="fas fa-edit me-2"></i> Edit Configuration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection