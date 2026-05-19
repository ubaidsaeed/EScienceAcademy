<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Subscriptionplans;
use App\Models\User;
use App\Models\Setting;
use App\Models\PageSection;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class Home extends Controller
{
    public function index(Request $request)
    {
        $pageSections = DB::table('page_section')->where('page_id', 1)->orderBy('priority', 'asc')->get();
        $subscription = DB::table('subscription_plans')
            ->join('levels', 'subscription_plans.level_id', '=', 'levels.id')
            ->orderBy('levels.id')
            ->select(
                'subscription_plans.*',
                'levels.id as level_id',
                'levels.name as level_name',
                'levels.slug as level_slug'
            )
            ->get();
            $featureRecords = DB::table('features')->where('status',1)->orderBy('sort_order','asc')->get();
            $planFeatures = [];
            foreach ($subscription as $plan) {
        // Get features
        $features = DB::table('subscription_plan_feature as spf')
            ->join('features as f', 'spf.feature_id', '=', 'f.id')
            ->where('spf.subscription_plan_id', $plan->id)
            ->where('f.status', 1)
            ->select('f.id', 'f.key', 'f.name', 'f.icon')
            ->get();
              $planFeatures[$plan->id] = $features;
            }
            // dd($planFeatures);
        $settings = Setting::first();
        $site_title = $settings?->site_title ?? '';
        $site_description = $settings?->site_description ?? '';

        // If a plan is selected (e.g., from "Subscribe Now" button)
        // $selectedPlan = null;
        // if ($request->has('plan')) {
        //     $selectedPlan = Subscriptionplans::findOrFail($request->get('plan'));
        // }

        return view('front-end.home', compact(
            'pageSections',
            'subscription', 
            'planFeatures',
            'featureRecords',
            'site_title',
            'site_description',
            
        ));
    }

    public function subscribeForm($planId)
    {
        $plan = Subscriptionplans::findOrFail($planId);

        return redirect()->route('home')->with('plan', $plan);
        // Or directly: return view('frontend.subscription-form', compact('plan'));
    }

    public function storeSubscription(Request $request)
    {
        $request()->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'date_of_birth' => ['required', 'date'],
            'number' => ['required', 'string'],
            'address' => ['required'],
            'city' => ['required'],
            'country' => ['required'],
            'pin_code' => ['required'],
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'gmale' => ['nullable', 'in:male'],
            'gfemale' => ['nullable', 'in:female'],
        ]);

        $plan = DB::table('subscription_plans')->find($request->plan_id);

        if (!$plan) {
            return back()->with('error', 'Invalid subscription plan.');
        }

        try {
            DB::beginTransaction();

            $gender = $request->gmale ?: $request->gfemale ?: '';

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_birth' => $request->date_of_birth,
                'number' => $request->number,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'pin_code' => $request->pin_code,
                'gender' => $gender,
                'role_id' => 44,
            ]);

            // Assign "student" role
            $role = Role::where('name', 'student')->first();
            if ($role) {
                $user->assignRole($role);
            }

            $dates = $this->calculateSubscriptionEndDate($plan->duration, $plan->duration_type);

            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'board_id' => $plan->board_id,
                'level_id' => $plan->level_id,
                'subject_id' => $plan->subject_id,
                'plan_price' => $plan->price,
                'payment_method_id' => 1,
                'payment_status' => 'pending',
                'status' => 'unactive',
                'created_at' => now(),
                'start_date' => $dates['start_date'],
                'end_date' => $dates['end_date'],
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    private function calculateSubscriptionEndDate($duration, $durationType)
    {
        if (!is_numeric($duration)) {
            throw new InvalidArgumentException("Duration must be numeric.");
        }

        $duration = (int) $duration;
        $startDate = Carbon::now();

        $endDate = match (strtolower($durationType)) {
            'days' => $startDate->copy()->addDays($duration),
            'months' => $startDate->copy()->addMonths($duration),
            'years' => $startDate->copy()->addYears($duration),
            default => throw new InvalidArgumentException("Invalid duration type: $durationType"),
        };

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}