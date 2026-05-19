<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboards extends Controller
{
    public function index(Request $request)
    {
        

       if (!auth()->user()->can('view dashboard')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        // Initialize with current month
        $startOfMonth = $request->input('startOfMonth', Carbon::now()->format('Y-m'));
        $endOfMonth = $request->input('endOfMonth', Carbon::now()->format('Y-m'));
        
        // Validate dates if submitted
        if ($request->isMethod('post')) {
            $request->validate([
                'startOfMonth' => 'required|date_format:Y-m',
                'endOfMonth' => 'required|date_format:Y-m',
            ], [
                'startOfMonth.required' => 'Please select a start date.',
                'endOfMonth.required' => 'Please select an end date.',
                'startOfMonth.date_format' => 'Start date must be in YYYY-MM format.',
                'endOfMonth.date_format' => 'End date must be in YYYY-MM format.',
            ]);
        }

        // Get subscriber data
        $data = $this->getSubscribers($startOfMonth, $endOfMonth, $request);

        return view('admin.dashboards', $data );
    }

    private function getSubscribers($startOfMonth, $endOfMonth, Request $request)
    {
        try {
            // Parse dates
            $startDate = Carbon::createFromFormat('Y-m', $startOfMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $endOfMonth)->endOfMonth();

            // Validate date range
            if ($startDate->gt($endDate)) {
                session()->flash('error', 'Start date cannot be after end date.');
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }

            // Calculate statistics
            $data = [
                'totalStudent' => DB::table('subscriptions')->count(),
                'totalIncome' => DB::table('subscriptions')
                    ->where('payment_status', 'paid')
                    ->sum('plan_price'),
                'totalActiveStudents' => DB::table('subscriptions')
                    ->where('status', 'active')
                    ->count(),
                'currentMonthTotalStudent' => DB::table('subscriptions')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'currentMonthTotalIncome' => DB::table('subscriptions')
                    ->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('plan_price'),
                'customQuery' => DB::table('custom_query')->count(),
                'startOfMonth' => $startOfMonth,
                'endOfMonth' => $endOfMonth,
            ];

            // Fetch subscription data for chart
            $completedData = DB::table('subscriptions')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupByRaw('DATE(created_at)')
                ->orderBy('date')
                ->pluck('total', 'date')
                ->toArray();

            // Generate date range
            $progress = [];
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $dateString = $current->toDateString();
                $progress[] = [
                    'date' => $dateString,
                    'total' => $completedData[$dateString] ?? 0,
                ];
                $current->addDay();
            }

            $data['progress'] = $progress;
            $data['progressJson'] = json_encode($progress);

            return $data;

        } catch (\Exception $e) {
            \Log::error('Error in getSubscribers: ' . $e->getMessage());
            
            // Return default data on error
            return [
                'totalStudent' => 0,
                'totalIncome' => 0,
                'totalActiveStudents' => 0,
                'currentMonthTotalStudent' => 0,
                'currentMonthTotalIncome' => 0,
                'customQuery' => 0,
                'startOfMonth' => $startOfMonth,
                'endOfMonth' => $endOfMonth,
                'progress' => [],
                'progressJson' => json_encode([]),
            ];
        }
    }
}