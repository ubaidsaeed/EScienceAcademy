<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class Subscribe extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view subscription')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        if ($request->ajax()) {
            return $this->getSubscriptionData($request);
        }
        
        return view('admin.subscribe.index');
    }

    private function getSubscriptionData(Request $request)
    {
        $query = DB::table('subscriptions')
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->join('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
            ->select(
                'subscriptions.id',
                'users.name as user_name',
                'users.email as user_email',
                'subscription_plans.name as plan_name',
                'subscriptions.grand_total as price',
                'subscriptions.status',
                'subscriptions.payment_status',
                'subscriptions.start_date',
                'subscriptions.end_date',
                'subscriptions.created_at'
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $actionBtn = '<div class="btn-list">';
                if (auth()->user()->can('edit subscription')) {
                $actionBtn .= '<a href="' . route('admin.subscribe.view', $row->id) . '" class="btn btn-primary btn-sm view-btn" data-id="' . $row->id . '">
                    <i class="fas fa-eye"></i>
                </a>';
                }
              
                $actionBtn .= '</div>';
                return $actionBtn;
            
            })
            ->addColumn('status_badge', function($row) {
                $statusClass = $row->status == 'active' ? 'badge-success' : 'badge-danger';
                return '<span class="badge ' . $statusClass . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('payment_status_badge', function($row) {
                if ($row->payment_status == 'paid') {
                    $class = 'badge-success';
                } elseif ($row->payment_status == 'pending') {
                    $class = 'badge-warning';
                } else {
                    $class = 'badge-danger';
                }
                return '<span class="badge ' . $class . '">' . ucfirst($row->payment_status) . '</span>';
            })
            ->addColumn('duration', function($row) {
                if ($row->start_date && $row->end_date) {
                    $start = Carbon::parse($row->start_date);
                    $end = Carbon::parse($row->end_date);
                    $diff = $start->diff($end);
                    
                    $duration = '';
                    if ($diff->y > 0) $duration .= $diff->y . ' years ';
                    if ($diff->m > 0) $duration .= $diff->m . ' months ';
                    if ($diff->d > 0) $duration .= $diff->d . ' days ';
                    
                    return trim($duration);
                }
                return 'N/A';
            })
            ->filterColumn('user_name', function($query, $keyword) {
                $query->where('users.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('plan_name', function($query, $keyword) {
                $query->where('subscription_plans.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('status', function($query, $keyword) {
                $query->where('subscriptions.status', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('payment_status', function($query, $keyword) {
                $query->where('subscriptions.payment_status', 'like', '%' . $keyword . '%');
            })
            ->rawColumns(['action', 'status_badge', 'payment_status_badge'])
            ->make(true);
    }

    public function view($id)
    {
        if (!auth()->user()->can('edit subscription')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
        }
        $data = [];
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        
        // Fetch the user's subscription
        $subscription = DB::table('subscriptions')
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->where('subscriptions.id', $id)
            ->select(
                'subscriptions.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->first();
        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found for this user. Please contact the admin.');
        }
        
        $plan = DB::table('subscription_plans')
        ->join('subscriptions', 'subscription_plans.id', '=', 'subscriptions.plan_id')
            ->where('subscription_plans.id', $subscription->plan_id)
            ->select(
                'subscription_plans.*',
                'subscriptions.grand_total'
                )
            ->first();
            
        $chapters = DB::table('subscription_plans')
            ->join('subscriptions', 'subscription_plans.id', '=', 'subscriptions.plan_id')
            ->join('subscription_chapters', 'subscription_chapters.subscription_id', '=', 'subscription_plans.id')
            ->join('folders AS child_folders', 'subscription_chapters.chapter_id', '=', 'child_folders.id')
            ->leftJoin('folders AS parent_folders', 'child_folders.parent_id', '=', 'parent_folders.id')
            ->leftJoin('folders AS main_parent_folders', 'parent_folders.parent_id', '=', 'main_parent_folders.id')
            ->leftJoin('folders AS main_first_parent_folders', 'main_parent_folders.parent_id', '=', 'main_first_parent_folders.id')
            ->where('subscription_plans.id', $plan->id)
            ->select(
                'child_folders.*',
                'parent_folders.name AS parent_name',
                'main_parent_folders.name AS main_parent_name',
                'main_first_parent_folders.name AS main_first_parent_name',
                'subscription_plans.name AS plan_name',
                'subscription_chapters.chapter_id',
                'subscriptions.grand_total as price',
                'subscription_plans.duration',
                'subscription_plans.duration_type',
            )
            ->latest('subscription_chapters.created_at')
            ->limit(3)
            ->get();
            
        $main_parent_name = [];
        foreach ($chapters as $item) {
            if (empty($main_parent_name)) {
                $main_parent_name = [
                    'main_parent_name' => $item->main_parent_name,
                    'price' => $item->price,
                    'plan_name' => $item->plan_name,
                    'duration' => $item->duration,
                    'duration_type' => $item->duration_type,
                    'main_first_parent_name' => $item->main_first_parent_name,
                ];
            }
        }
        
        // Calculate completion percentage
        $startDate = $subscription->start_date ? Carbon::parse($subscription->start_date) : now();
        $endDate = $subscription->end_date ? Carbon::parse($subscription->end_date) : now();
        
           
        $currentDate = Carbon::now();
        $totalDuration = $startDate->diffInDays($endDate);
        $completedDuration = $startDate->diffInDays($currentDate);
        $completionPercentage = ($totalDuration > 0) ? ($completedDuration / $totalDuration) * 100 : 100;
        $completionPercentage = round(min(100, max(0, $completionPercentage)));
        $completedDurationFormatted = number_format($completedDuration, 0);

        // Get Top Three Courses
        $courses = DB::table('subscription_plans')
            ->join('subscription_chapters', 'subscription_chapters.subscription_id', '=', 'subscription_plans.id')
            ->join('media AS media_file', 'subscription_chapters.chapter_id', '=', 'media_file.model_id')
            ->where('subscription_plans.id', $subscription->plan_id)
            ->select('subscription_chapters.chapter_id', DB::raw('COUNT(media_file.id) as media_count'))
            ->groupBy('subscription_chapters.chapter_id')
            ->get();
            
        $completed_files = DB::table('completed_files')
            ->select('model_id', DB::raw('COUNT(file_id) as completed_count'))
            ->where('user_id', Auth::user()->id)
            ->where('completed', 1)
            ->groupBy('model_id')
            ->get();
            
        $completed_lectures = DB::table('completed_files')
            ->where('user_id', Auth::user()->id)
            ->where('completed', 1)
            ->count();
            
        $notifications = DB::table('notification')
            ->join('subscription_notification', 'subscription_notification.notification_id', '=', 'notification.id')
            ->where('subscription_notification.subscription_plan_id', $subscription->plan_id)
            ->where('notification.status', 'active')
            ->get();
            
        return view('admin.subscribe.view', [
            'subscription' => $subscription,
            'user' => $user,
            'totalDuration' => $totalDuration,
            'courses_lectures' => $courses,
            'completedDuration' =>$completedDurationFormatted,
            'chapters_record' => $chapters,
            'plan' => $plan,
            'main_parent_name' => $main_parent_name,
            'completionPercentage' => $completionPercentage,
            'completed_files' => $completed_files,
            'completed_lectures' => $completed_lectures,
            'notifications' => $notifications
        ]);
    }
    
    public function updateSubscription(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'payment_status' => 'required',
        ]);
        
        try {
            $subscribe = DB::table('subscriptions')->where('id', $request->subscription_id)->first();
            $subcription_plan = DB::table('subscription_plans')->where('id', $subscribe->plan_id)->first();
            
             $subscriptionDate = [
                'duration' => $subcription_plan->duration,
                'duration_type' => $subcription_plan->duration_type,
            ];
             $result = $this->calculateSubscriptionEndDate($subscriptionDate['duration'], $subscriptionDate['duration_type']);
            DB::table('subscriptions')->where('id', $request->subscription_id)->update([
                'start_date' => $result['start_date'],
                'end_date' => $result['end_date'],
                'status' => $request->status,
                'payment_status' => $request->payment_status
            ]);
            
            if ($request->status == 'active' && $request->payment_status == 'paid') {
                $users = User::find($subscribe->user_id);
                $role = Role::where('name', 'student')->first();
                
                DB::table('users')->where('id', $subscribe->user_id)->update([
                    'role_id' => $role->id
                ]);
                
                // $endDate = $this->calculateSubscriptionEndDate($subcription_plan->duration, $subcription_plan->duration_type);
                
                // DB::table('subscriptions')->where('id', $request->subscription_id)->update([
                //     'start_date' => now()->format('Y-m-d'),
                //     'end_date' => $endDate->format('Y-m-d'),
                // ]);
                
                $users->assignRole($role->id);
            }
            
            return redirect()->route('admin.subscribe.index')->with('success', 'Subscription updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again later');
        }
    }
    
   public function calculateSubscriptionEndDate($duration, $durationType)
    {
        

        // Ensure duration is numeric
        if (!is_numeric($duration)) {
            throw new InvalidArgumentException("Duration must be a numeric value. Given: $duration");
        }

        // Cast duration to an integer or float
        $duration = (int) $duration;

        // Start date
        $startDate = Carbon::now();
        // Calculate end date based on duration type
        switch (strtolower($durationType)) {
            case 'days':
                $endDate = $startDate->copy()->addDays($duration);
                break;
            case 'months':
                $endDate = $startDate->copy()->addMonths($duration);
                break;
            case 'years':
                $endDate = $startDate->copy()->addYears($duration);
                break;
            default:
                throw new InvalidArgumentException("Invalid duration type: $durationType. Allowed values are 'day', 'month', 'year'.");
        }
        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
    
    // public function subList($id)
    // {
    //     $subscription = DB::table('subscriptions')
    //         ->join('users', 'subscriptions.user_id', '=', 'users.id')
    //         ->where('subscriptions.id', $id)
    //         ->select('subscriptions.*', 'users.name as user_name', 'users.email as user_email')
    //         ->first();
            
    //     return view('admin.subscribe.assignments', [
    //         'subscription' => $subscription
    //     ]);
    // }
}