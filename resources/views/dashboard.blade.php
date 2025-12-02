@extends('layout.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid py-5">

        <div class="mb-4">
            <h2 class="fw-bold mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Overview of platform activity</p>
        </div>

        <div class="row g-4">

            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card stat-card users-card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1">Total Users</h6>
                                    <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                                    <small class="text-muted">Registered Users</small>
                                </div>
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="mt-3 text-primary small fw-semibold">
                                View all users →
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card stat-card credits-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">Total Credits</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($totalCredits ?? 0) }}</h2>
                                <small class="text-muted">All Users Combined</small>
                            </div>
                            <div class="icon-box bg-info">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-info small fw-semibold">
                            Credit Summary
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card stat-card active-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">Active Subscriptions</h6>
                                <h2 class="fw-bold mb-0">{{ $activeUsers ?? 0 }}</h2>
                                <small class="text-muted">Currently Active</small>
                            </div>
                            <div class="icon-box bg-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-success small fw-semibold">
                            Subscription Health
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card stat-card revenue-card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase text-muted mb-1">Total Revenue</h6>
                                <h2 class="fw-bold mb-0">₹ {{ number_format($totalRevenue ?? 0) }}</h2>
                                <small class="text-muted">All Time</small>
                            </div>
                            <div class="icon-box bg-warning">
                                <i class="fas fa-indian-rupee-sign"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-warning small fw-semibold">
                            Payment Summary
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <style>
        .stat-card {
            border-radius: 18px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
        }

        .icon-box {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .users-card .icon-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .credits-card .icon-box {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
        }

        .active-card .icon-box {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .revenue-card .icon-box {
            background: linear-gradient(135deg, #f7971e, #ffd200);
        }

        .stat-card h2 {
            font-size: 30px;
        }

        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 26px;
            }
        }
    </style>
@endsection
