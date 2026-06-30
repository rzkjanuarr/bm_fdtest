<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $emailVerified = $request->input('email_verified', '');

        $users = User::when($search, function ($query, $search) {
            $query->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('email', 'ilike', '%' . $search . '%');
        })->when($status !== '', function ($query) use ($status) {
            $query->where('status', $status === '1');
        })->when($emailVerified !== '', function ($query) use ($emailVerified) {
            if ($emailVerified === '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        })->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get weekly user growth data (last 7 days)
        $today = now();
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->locale('id')->shortDayName;
            $values[] = User::whereDate('created_at', $date->toDateString())->count();
        }

        // New users this month
        $newUsersThisMonth = User::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->where('status', 1)->count();

        // Active users count for sidebar badge
        $activeUsersCount = User::where('status', 1)->count();
        $totalBooksCount = Book::count();

        return Inertia::render('User', [
            'users' => $users,
            'weeklyLabels' => $labels,
            'weeklyValues' => $values,
            'newUsersThisMonth' => $newUsersThisMonth,
            'search' => $search,
            'status' => $status,
            'emailVerified' => $emailVerified,
            'activeUsersCount' => $activeUsersCount,
            'totalBooksCount' => $totalBooksCount
        ]);
    }

    public function dashboard()
    {
        $today = now();

        // User data
        $totalUsers = User::where('status', 1)->count();
        $newUsersThisMonth = User::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->where('status', 1)->count();
        $userWeeklyLabels = [];
        $userWeeklyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $userWeeklyLabels[] = $date->locale('id')->shortDayName;
            $userWeeklyValues[] = User::whereDate('created_at', $date->toDateString())->count();
        }

        // Book data
        $totalBooks = Book::count();
        $newBooksThisMonth = Book::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count();
        $bookWeeklyLabels = [];
        $bookWeeklyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $bookWeeklyLabels[] = $date->locale('id')->shortDayName;
            $bookWeeklyValues[] = Book::whereDate('created_at', $date->toDateString())->count();
        }

        return Inertia::render('Dashboard', [
            'totalUsers' => $totalUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'userWeeklyLabels' => $userWeeklyLabels,
            'userWeeklyValues' => $userWeeklyValues,
            'totalBooks' => $totalBooks,
            'newBooksThisMonth' => $newBooksThisMonth,
            'bookWeeklyLabels' => $bookWeeklyLabels,
            'bookWeeklyValues' => $bookWeeklyValues,
            'totalBooksCount' => $totalBooks
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:SUPER ADMIN,USER',
            'status' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('user');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:SUPER ADMIN,USER',
            'status' => 'required|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('user');
    }

    public function destroy(User $user)
    {
        $user->update(['status' => 0]);
        return redirect()->route('user');
    }
}
