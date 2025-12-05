@extends('layout.app')

@section('title', 'All Users')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1">User Management</h3>
                <p class="text-muted mb-0">Manage and monitor all registered users</p>
            </div>
            <div class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fs-6 rounded-pill shadow-sm">
                {{ $users->total() }} Total Users
            </div>
        </div>

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

            <div class="card-header bg-white border-bottom py-4">
                <form method="GET" action="{{ route('admin.users') }}" class="row g-3 align-items-end">

                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="form-label small text-muted">Search Users</label>
                        <div class="input-group shadow-sm">
                            <span class="bg-light border-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0"
                                placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Subscription Status</label>
                        <select name="subscription_status" class="form-select shadow-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>Active
                                Only</option>
                            <option value="inactive" {{ request('subscription_status') == 'inactive' ? 'selected' : '' }}>
                                Inactive Only</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Plan Duration</label>
                        <select name="duration" class="form-select shadow-sm">
                            <option value="">All</option>
                            <option value="1" {{ request('duration') == '1' ? 'selected' : '' }}>1 Month</option>
                            <option value="3" {{ request('duration') == '3' ? 'selected' : '' }}>3 Months</option>
                            <option value="6" {{ request('duration') == '6' ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ request('duration') == '12' ? 'selected' : '' }}>12 Months</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small text-muted">Sort By</label>
                        <select name="sort" class="form-select shadow-sm">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="credits_high" {{ request('sort') == 'credits_high' ? 'selected' : '' }}>Credits
                                High</option>
                            <option value="credits_low" {{ request('sort') == 'credits_low' ? 'selected' : '' }}>Credits
                                Low</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label class="form-label small text-muted d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill">Apply</button>
                    </div>

                    @if (request('search') || request('subscription_status') || request('sort') || request('duration'))
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
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
                                <th>User</th>
                                <th class="text-center">Credits</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Duration</th>
                                <th>Subscription Period</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-bottom table-row-hover">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                                <div class="text-muted small">
                                                    Joined {{ $user->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                            {{ number_format($user->total_credits) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if ($user->is_subscribed)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                                        @else
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if (!empty($user->duration_months))
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                {{ $user->duration_months }} Months
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($user->subscription_start && $user->subscription_end)
                                            <div class="small">
                                                <div><strong>Start:</strong>
                                                    {{ \Carbon\Carbon::parse($user->subscription_start)->format('M d, Y') }}
                                                </div>
                                                <div><strong>End:</strong>
                                                    {{ \Carbon\Carbon::parse($user->subscription_end)->format('M d, Y') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">No subscription</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($users->hasPages())
                <div class="card-footer bg-white border-top py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="small text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </div>

                        <nav>
                            <ul class="pagination pagination-custom mb-0">
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $users->previousPageUrl() }}">Prev</a></li>
                                @endif

                                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($users->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                                    </li>
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
        .avatar-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600
        }

        .card {
            transition: 0.3s
        }

        .table-row-hover:hover {
            background: #f8f9fd
        }

        .pagination-custom .page-link {
            border-radius: 10px;
            border: none;
            margin: 0 4px;
            color: #667eea;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08)
        }

        .pagination-custom .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff
        }

        .pagination-custom .page-link:hover {
            background: #667eea;
            color: #fff
        }
    </style>
@endsection
