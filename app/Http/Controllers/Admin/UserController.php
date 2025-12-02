<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select(
                'users.*',
                'subscription_plans.duration_months'
            )
            ->leftJoin('subscription_plans', 'users.plan_id', '=', 'subscription_plans.id');

        // ✅ SEARCH (UNCHANGED)
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('users.email', 'like', '%' . $searchTerm . '%');
            });
        }

        // ✅ SUBSCRIPTION STATUS (UNCHANGED)
        if ($request->filled('subscription_status')) {
            if ($request->subscription_status == 'active') {
                $query->where('users.is_subscribed', true);
            } elseif ($request->subscription_status == 'inactive') {
                $query->where('users.is_subscribed', false);
            }
        }

        // ✅ SORT (UNCHANGED)
        if ($request->filled('sort')) {
            if ($request->sort == 'newest') {
                $query->orderBy('users.id', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('users.id', 'asc');
            } elseif ($request->sort == 'credits_high') {
                $query->orderBy('users.total_credits', 'desc');
            } elseif ($request->sort == 'credits_low') {
                $query->orderBy('users.total_credits', 'asc');
            }
        } else {
            $query->orderBy('users.id', 'desc');
        }

        // ✅ ✅ DURATION FILTER (FROM subscription_plans)
        if ($request->filled('duration')) {
            $query->where('subscription_plans.duration_months', $request->duration);
        }

        // ✅ PAGINATION (UNCHANGED)
        $users = $query->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }
}
