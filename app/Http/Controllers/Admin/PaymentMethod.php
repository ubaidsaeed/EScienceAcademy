<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class PaymentMethod extends Controller
{
    public function index()
{
    // if (!Auth::check()) {
    //     return redirect()->route('login');
    // }

    $userId = Auth::id();
    
    // Get user's existing subscriptions
    $activeSubscription = DB::table('subscriptions')
        ->where('user_id', $userId)
        ->where('status', 'active')
        ->first();
    
    $inactiveSubscription = DB::table('subscriptions')
        ->where('user_id', $userId)
        ->where('status', 'unactive')
        ->first();

    // Get all active subscription plans
    $subscriptionPlans = DB::table('subscription_plans as sp')
        ->select(
            'sp.id',
            'sp.name',
            'sp.slug',
            'sp.content',
            'sp.price as plan_price',
            'sp.duration',
            'sp.duration_type',
            'sp.board_id',
            'sp.level_id',
            'sp.status',
            DB::raw('(SELECT name FROM boards WHERE id = sp.board_id) as board_name'),
            DB::raw('(SELECT name FROM levels WHERE id = sp.level_id) as level_name')
        )
        ->where('sp.status', 'active')
        ->get();

    // Get features for each plan
    $planFeatures = [];
    $planSubjects = [];
    
    foreach ($subscriptionPlans as $plan) {
        // Get features
        $features = DB::table('subscription_plan_feature as spf')
            ->join('features as f', 'spf.feature_id', '=', 'f.id')
            ->where('spf.subscription_plan_id', $plan->id)
            ->where('f.status', 1)
            ->select('f.id', 'f.key', 'f.name', 'f.icon')
            ->get();
        
        $planFeatures[$plan->id] = $features;

        // Get subjects
        $subjects = DB::table('subscription_plan_subject as sps')
            ->join('subjects as s', 'sps.subject_id', '=', 's.id')
            ->where('sps.subscription_plan_id', $plan->id)
            ->where('s.status', 'active')
            ->select('s.id', 's.name', 's.slug', 'sps.subject_price')
            ->get();
        
        $planSubjects[$plan->id] = $subjects;
    }
    
    // Get boards and levels
    $boards = DB::table('boards')->where('status', 'active')->get();
    $levels = DB::table('levels')->where('status', 'active')->get();
    
    // Get active subscription details if exists
    $activeSubscriptionDetails = null;
    $activeSubscriptionSubjects = [];
    $subjectTotal = 0;
    $grandTotal = 0;
    $existingSubscription = $activeSubscription ?? $inactiveSubscription;
    
    if ($activeSubscription) {
        // Get subscription details with plan info
        $activeSubscriptionDetails = DB::table('subscriptions as s')
            ->select(
                's.*',
                'sp.name as plan_name',
                'sp.duration as plan_duration',
                'sp.duration_type',
                'b.name as board_name',
                'l.name as level_name'
            )
            ->leftJoin('subscription_plans as sp', 's.plan_id', '=', 'sp.id')
            ->leftJoin('boards as b', 's.board_id', '=', 'b.id')
            ->leftJoin('levels as l', 's.level_id', '=', 'l.id')
            ->where('s.id', $activeSubscription->id)
            ->first();
        
        // Get selected subjects with their prices
        $activeSubscriptionSubjects = DB::table('subscription_subjects as ss')
            ->join('subjects as sub', 'ss.subject_id', '=', 'sub.id')
            ->join('subscription_plan_subject as sps', function($join) use ($activeSubscription) {
                $join->on('ss.subject_id', '=', 'sps.subject_id')
                     ->where('sps.subscription_plan_id', $activeSubscription->plan_id);
            })
            ->where('ss.subscription_plan_id', $activeSubscription->id)
            ->select(
                'sub.id',
                'ss.free as free_subject_id',
                'sub.name',
                'sub.slug',
                'sps.subject_price'
            )
            ->get();
            // dd($activeSubscriptionSubjects);
        // Calculate subject total and grand total
        foreach ($activeSubscriptionSubjects as $subject) {
            $subjectTotal += $subject->subject_price;
        }
        
        // Calculate grand total based on duration
        $subjectTotalForDuration = $subjectTotal * $activeSubscription->duration_months;
        $planTotal = $activeSubscription->plan_price * $activeSubscription->duration_months;
        $grandTotal = $planTotal + $subjectTotalForDuration;
        
        // Add calculated totals to subscription details
        $activeSubscriptionDetails->subject_total = $subjectTotal;
        $activeSubscriptionDetails->subject_total_for_duration = $subjectTotalForDuration;
        $activeSubscriptionDetails->plan_total_for_duration = $planTotal;
        $activeSubscriptionDetails->grand_total = $grandTotal;
    }
    
    return view('student.payment-method', compact(
        'existingSubscription',
        'activeSubscriptionDetails',
        'activeSubscriptionSubjects',
        'subjectTotal',
        'grandTotal',
        'subscriptionPlans',
        'planFeatures',
        'planSubjects',
        'boards',
        'levels'
    ));
}
    public function getPlanDetails($planId)
    {
        try {
            // Get plan details
            $plan = DB::table('subscription_plans')
                ->where('id', $planId)
                ->where('status', 'active')
                ->first();

            if (!$plan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plan not found'
                ], 404);
            }

            // Get plan features - FIXED QUERY
            $features = DB::table('subscription_plan_feature as spf')
                ->join('features as f', 'spf.feature_id', '=', 'f.id')
                ->where('spf.subscription_plan_id', $planId)
                ->where('f.status', 1)
                ->select('f.id', 'f.key', 'f.name', 'f.icon')
                ->get();
            $featureRecords = DB::table('features')->where('status',1)->orderBy('sort_order','asc')->get();
            // Get plan subjects with prices
            $subjects = DB::table('subscription_plan_subject as sps')
                ->join('subjects as s', 'sps.subject_id', '=', 's.id')
                ->where('sps.subscription_plan_id', $planId)
                ->where('s.status', 'active')
                ->select('s.id', 's.name', 's.slug', 'sps.subject_price')
                ->get();

            // Get board and level info
            $board = DB::table('boards')->where('id', $plan->board_id)->first();
            $level = DB::table('levels')->where('id', $plan->level_id)->first();

            return response()->json([
                'success' => true,
                'plan' => $plan,
                'features' => $features,
                'featureRecords' =>$featureRecords,
                'subjects' => $subjects,
                'board' => $board,
                'level' => $level
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching plan details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration' => 'required|integer|min:1',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        try {
            $planId = $request->plan_id;
            $duration = $request->duration;
            $subjectIds = $request->subject_ids;

            // Get base plan price
            $plan = DB::table('subscription_plans')
                ->where('id', $planId)
                ->first();

            // Get subject prices for this plan
            $subjectPrices = DB::table('subscription_plan_subject')
                ->where('subscription_plan_id', $planId)
                ->whereIn('subject_id', $subjectIds)
                ->pluck('subject_price', 'subject_id')
                ->toArray();

            // Calculate total
            $subjectTotal = 0;
            foreach ($subjectIds as $subjectId) {
                $subjectTotal += $subjectPrices[$subjectId] ?? 0;
            }
            
            $totalPrice = $subjectTotal * $duration;

            // Get subject names
            $subjects = DB::table('subjects')
                ->whereIn('id', $subjectIds)
                ->select('id', 'name')
                ->get();

            $breakdown = [];
            foreach ($subjects as $subject) {
                $subjectPrice = $subjectPrices[$subject->id] ?? 0;
                $breakdown[] = [
                    'name' => $subject->name,
                    'price' => $subjectPrice,
                    'total' => $subjectPrice * $duration
                ];
            }

            return response()->json([
                'success' => true,
                'plan_price' => $plan->price,
                'subject_total' => $subjectTotal,
                'duration' => $duration,
                'total_price' => $totalPrice,
                'breakdown' => $breakdown,
                'currency' => 'PKR'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating price: ' . $e->getMessage()
            ], 500);
        }
    }

   public function processCheckout(Request $request)
{
    $request->validate([
        'plan_id' => 'required|exists:subscription_plans,id',
        'subject_ids' => 'required|array',
        'duration' => 'required|integer|min:1',
        'total_amount' => 'required|numeric'
    ]);
    // return response()->json($request);
    try {
        $userId = Auth::id();
        $planId = $request->plan_id;
        $subjectIds = $request->subject_ids;
        $duration = $request->duration;
        $totalAmount = $request->total_amount;
        $subjectDetails = $request->subject_details;

        // Get plan details
        $plan = DB::table('subscription_plans')
            ->where('id', $planId)
            ->first();
            
              DB::table('subscriptions')
            ->where('user_id', $userId)
            ->update([
                'status' => 'unactive',
                'payment_status' => 'pending',
                'updated_at' => now()
            ]);
        // Check if user already has ANY pending/unactive subscription
        $existingSubscription = DB::table('subscriptions')
            ->where('user_id', $userId)
            ->where('status', 'unactive')
            ->where('payment_status', 'pending')
            ->orderBy('id', 'desc') // Get the latest one
            ->first();
            // Delete old selected subjects
            DB::table('subscription_subjects')
                ->where('subscription_plan_id', $existingSubscription->id)
                ->delete();
        if ($existingSubscription) {
            // UPDATE existing subscription (even if different plan)
            $subscriptionId = $existingSubscription->id;
            // Get subject details
        $subjects = DB::table('subjects')
            ->whereIn('id', $subjectIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');

        // Get existing free subjects from current subscription
        $existingFreeSubjects = DB::table('subscription_subjects')
            ->where('subscription_plan_id', $subscriptionId)
            ->whereNotNull('free')
            ->pluck('free')
            ->toArray();
            DB::table('subscriptions')
                ->where('id', $subscriptionId)
                ->update([
                    'plan_id' => $planId, // Update plan ID too
                    'plan_price' => $plan->price,
                    'subjects_price' => $subjectsPrice ?? 0,
                    'grand_total' => $totalAmount,
                    'board_id' => $plan->board_id,
                    'level_id' => $plan->level_id,
                    'duration_months' => $duration,
                    'updated_at' => now()
                ]);
            
            
            
            $action = 'updated';
        } else {
            // CREATE new subscription
            $subscriptionId = DB::table('subscriptions')->insertGetId([
                'plan_id' => $planId,
                'plan_price' => $plan->price,
                'subjects_price' => $request->subjects_total ?? 0,
                'grand_total' => $totalAmount,
                'user_id' => $userId,
                'board_id' => $plan->board_id,
                'level_id' => $plan->level_id,
                'duration_months' => $duration,
                'payment_status' => 'pending',
                'status' => 'unactive',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $action = 'created';
        }
        $userScription = DB::table('subscriptions')
        ->join('users', 'subscriptions.user_id', '=', 'users.id')
        // ->where([['users.id', Auth::id()], 'subscriptions.status', 'unactive'])
        ->where([
            ['users.id', Auth::id()],
            ['subscriptions.status', 'unactive']
        ])
        //   ->latest('created_at')
        ->select('subscriptions.*')
        ->first();
        // Store selected subjects
        // foreach ($subjectIds as $subjectId) {
        //     DB::table('subscription_subjects')->insert([
        //         'subscription_plan_id' => $subscriptionId,
        //         'subject_id' => $subjectId,
        //         'free' => $request->free_subject_id,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ]);
        // }
        // Store new selected subjects
       foreach ($subjectDetails as $subjectDetail) {
            $subjectId = $subjectDetail['subject_id'];
            $subject = $subjects[$subjectId] ?? null;
            
            if (!$subject) {
                continue;
            }
            
            // Determine if this subject is free based on the subject_details
            $isFree = $subjectDetail['is_free'] ?? false;
            
            DB::table('subscription_subjects')->insert([
                'subscription_plan_id' => $subscriptionId,
                'subject_id' => $subjectId,
                'free' => $isFree ? $subjectId : null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

         if ($userScription->payment_status == 'pending') {

            // Get the last payment attempt for this subscription
        $lastAttempt = DB::table('payment_attempts')
            ->where('subscription_id', $userScription->id)
            ->orderByDesc('attempt_no')
            ->first();

        // If no attempts yet, start with attempt 1
        $attemptNo = $lastAttempt ? $lastAttempt->attempt_no + 1 : 1;

        // Store the attempt in DB for tracking
        DB::table('payment_attempts')->insert([
            'subscription_id' => $userScription->id,
            'attempt_no' => $attemptNo,
            'sub_attempt_id' => $userScription->id . '-' . $attemptNo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
            $attempt_id = $userScription->id . '-' . $attemptNo;
            $orderId =  "{$userScription->id}-{$attemptNo}";
           return response()->json([
            'success' => true,
            'message' => 'Subscription ' . $action . ' successfully',
            'subscription_id' => $orderId,
            'action' => $action,
            'total_amount' => $totalAmount,
            'subscription_details' => [
                'plan_name' => $plan->name, 
                'duration' => $duration,
                'subjects_count' => count($subjectIds)
            ]
        ]);
        } else {
            return redirect()->route('student.dashboard');
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error processing checkout: ' . $e->getMessage()
        ], 500);
    }
}
// Add these methods to your PaymentMethodController class

/**
 * Handle payment response from Bank Alfalah
 */
public function paymentResponse($id)
{
    try {
        // Parse the attempt ID (format: subscriptionId-attemptNo)
        $payment_attemp =  DB::table('payment_attempts')
            ->where('sub_attempt_id', $id)
            ->first();

        $order = DB::table('subscriptions')->where('id', $payment_attemp->subscription_id)
        ->first();

    $HS_MerchantId = 31080;
    $HS_StoreId = '043966';
    $url = "https://payments.bankalfalah.com/HS/api/IPN/OrderStatus/{$HS_MerchantId}/{$HS_StoreId}/{$id}";

        $response = Http::get($url);
        if ($response->successful()) {
             $data = json_decode($response->json(), true);
            
            if (!isset($data['TransactionStatus']) || $data['TransactionStatus'] == 'Paid'){
                // Payment successful
               
                return redirect()->route('payment.success', ['id' => $id]);
            }elseif (!isset($data['TransactionStatus']) || $data['TransactionStatus'] == 'Failed') {
                // Payment failed
                return redirect()->route('paymentfailed')->with('error', 'Payment not confirmed.');
            }
        } else {
            // API call failed
            return redirect()->route('paymentfailed')->with('error', 'Could not verify payment status.');
        }
        
    } catch (\Exception $e) {
        \Log::error('Payment response error: ' . $e->getMessage());
        return redirect()->route('paymentfailed')->with('error', 'An error occurred while processing payment.');
    }
}

/**
 * Handle payment cancellation
 */
public function paymentCancel()
{
    return redirect()->route('payment.method')
        ->with('warning', 'Payment was cancelled. You can try again.');
}

/**
 * Show payment success page
 */
public function paymentSuccess($id)
{
    try {
        
        $payment_attemp =  DB::table('payment_attempts')
            ->where('sub_attempt_id', $id)
            ->first();

        $subscription = DB::table('subscriptions')->where('id', $payment_attemp->subscription_id)
        ->first();
        // dd($subscription);
        // Update subscription status
        DB::table('subscriptions')
            ->where('id', $payment_attemp->subscription_id)
            ->update([
                'payment_status' => 'paid',
                'status' => 'active',
                'updated_at' => now()
            ]);
        // Get plan details to calculate end date
        $plan = DB::table('subscription_plans')
            ->where('id', $subscription->plan_id)
            ->first();
        
        if ($plan) {
            $subscriptionDate = [
                'duration' => $plan->duration,
                'duration_type' => $plan->duration_type,
            ];
            // Calculate subscription end date
            $result = $this->calculateSubscriptionEndDate($subscriptionDate['duration'], $subscriptionDate['duration_type']);
        //   dd($subscription->id);
            // Update subscription with dates
            DB::table('subscriptions')
                ->where('id', $subscription->id)
                ->update([
                    'start_date' => $result['start_date'],
                    'end_date' => $result['end_date'],
                    'updated_at' => now()
                ]);
        }
        // Update user role if needed
        $user = User::find($subscription->user_id);
        if ($user) {
            $role = Role::where('name', 'Student')->first();
            if ($role) {
                $user->role_id = $role->id;
                $user->save();
                $user->assignRole($role->id);
            }
        }
        
        // Get subscription details for the view
        $subscriptionDetails = DB::table('subscriptions as s')
            ->select(
                's.*',
                'sp.name as plan_name',
                'b.name as board_name',
                'l.name as level_name'
            )
            ->leftJoin('subscription_plans as sp', 's.plan_id', '=', 'sp.id')
            ->leftJoin('boards as b', 's.board_id', '=', 'b.id')
            ->leftJoin('levels as l', 's.level_id', '=', 'l.id')
            ->where('s.id', $subscription->id)
            ->first();
       
        return view('student.payment-success', compact('subscriptionDetails'));
        
    } catch (\Exception $e) {
        \Log::error('Payment success error: ' . $e->getMessage());
        dd($e->getMessage);
        // return redirect()->route('paymentfailed')->with('error', 'An error occurred while processing your payment.');
    }
}

/**
 * Show payment failed page
 */
public function paymentFailed()
{
    return view('student.payment-failed');
}

/**
 * Show thank you page (alternative to payment success)
 */
public function thankYou()
{
    // Get the user's latest active subscription
    $userId = Auth::id();
    
    $subscription = DB::table('subscriptions as s')
        ->select(
            's.*',
            'sp.name as plan_name',
            'b.name as board_name',
            'l.name as level_name'
        )
        ->leftJoin('subscription_plans as sp', 's.plan_id', '=', 'sp.id')
        ->leftJoin('boards as b', 's.board_id', '=', 'b.id')
        ->leftJoin('levels as l', 's.level_id', '=', 'l.id')
        ->where('s.user_id', $userId)
        ->where('s.payment_status', 'paid')
        ->where('s.status', 'active')
        ->orderBy('s.created_at', 'desc')
        ->first();
    
    return view('student.thank-you', compact('subscription'));
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
}