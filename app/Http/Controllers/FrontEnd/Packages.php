<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Packages extends Controller
{
      public $id;

    // Capture the route parameter using mount()
    public function __construct($slug = null)
    {
        $this->id = $slug;
    }

    public function index($slug = null)
    {
        
        return view('front-end.packages',['slug']);
    }

    public function subscribe($slug)
    {
         $existingUser = User::find($this->id);

        // dd($this->id);
        $subcription = DB::table('subscription_plans')->where('slug', $slug)->first();
        if ($subcription) {
            DB::table('subscriptions')->insert([
                'user_id' => $existingUser->id,
                'plan_id' => $subcription->id,
                'board_id' => $subcription->board_id,
                'level_id' => $subcription->level_id,
                'subject_id' => $subcription->subject_id,
                'plan_price' => $subcription->price,
                'payment_method_id' => 1,
                'payment_status' => 'pending',
                 'created_at' => now(),
                'status' => 'unactive',
                // 'start_date' => ,
                // 'end_date' => 
            ]);
                     Auth::login($existingUser);
        }
       
            return redirect()->route('student.payment.method');
    }
}
