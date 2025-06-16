<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data['user_count'] = User::count();
        $data['book_count'] = Book::count();
        $data['user_recent'] = User::latest()->paginate(4);

        // Hitung jumlah pengguna per bulan (12 bulan terakhir)
        $monthlyUsers = [];
        $currentDate = Carbon::now()->startOfMonth();
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = $currentDate->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $count = User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlyUsers[$monthStart->format('M')] = $count;
        }

        $data['monthly_users'] = $monthlyUsers;

        return view('admin.dashboard', compact('user', 'data'));
    }
}