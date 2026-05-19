
@php
 $user_id = Auth()->user()->id;
        $user = \App\Models\User::find($user_id);
        // Fetch the user's subscription
        $subscription =\Illuminate\Support\Facades\DB::table('subscriptions')->where('user_id',$user->id)->first();
        if(isset($subscription)){
            $notifications =Illuminate\Support\Facades\DB::table('notification')
                ->join('subscription_notification', 'subscription_notification.notification_id', '=', 'notification_id')
                ->where('subscription_notification.subscription_plan_id', $subscription->plan_id)
                ->where('notification.status', 'active')
                ->select(
                    'notification.*',
                    'subscription_notification.subscription_plan_id',
                    )
                ->get();
                }
                
@endphp
<!--Footer-->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                Copyright © @php echo date('Y'); @endphp <a href="https://livebits.pk/">LiveBits</a>. 
                <!--<span class="fa fa-heart text-danger"></span>-->
                    All rights reserved.
            </div>
        </div>
    </div>
</footer>
<!-- End Footer-->
<!--sidebar-right-->
<div class="sidebar sidebar-right sidebar-animate">
    <div class="card-header border-bottom pb-5">
        <h4 class="card-title">Notifications </h4>
        <div class="card-options">
            <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-light text-primary"
                data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right"><i class="feather feather-x"></i> </a>
        </div>
    </div>
    <div class="">
        @isset($notifications)
        @foreach($notifications as $value)
        @php
        $dateAgo = $value->created_at;
        $dateAgo = \Carbon\Carbon::parse($dateAgo)->diffForHumans();
    @endphp
        <div class="list-group-item  align-items-center border-0">
            <div class="d-flex">
                <span class="avatar avatar-lg brround me-3"
                    style="background-image: url({{asset('build/assets/admin/images/users/4.jpg')}})"></span>
                <div class="mt-1 w-65">
                    {{-- <a href="{{ route('student.notification', $value->id)}}" class="font-weight-semibold fs-16">{{ $value->title }}</a> --}}
                    <span class="clearfix"></span>
                    <span class="text-muted fs-13 ms-auto"><i class="mdi mdi-clock text-muted me-1"></i>
                        @php
                        $now = \Carbon\Carbon::now(); // Current date and time
                        $targetDate = \Carbon\Carbon::parse($value->date); // Target date

                        // Calculate days left (integer only)
                        $daysLeft = $targetDate->diffInDays($now, false); // Include negatives for past dates
                        $dateAgo = floor($daysLeft);
                    @endphp

                    {{-- Display the message --}}
                    @if ($dateAgo > 0)
                        Expired {{ abs($dateAgo) }} day{{ abs($dateAgo) > 1 ? 's' : '' }} ago
                    @elseif($dateAgo == 0)
                        Today
                    @else
                        {{ abs($dateAgo) }} day{{ abs($dateAgo) > 1 ? 's' : '' }} to left
                    @endif    
                    </span>
                </div>
                <div class="ms-auto">
                    <a href="" class="me-0 option-dots" data-bs-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="feather feather-more-horizontal"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        {{-- <li><a href="{{ route('student.notification', $value->id)}}"><i class="feather feather-eye me-2"></i>View</a></li> --}}
                        {{-- <li><a href="javascript:void(0);"><i class="feather feather-plus-circle me-2"></i>Add</a></li>
                        <li><a href="javascript:void(0);"><i class="feather feather-trash-2 me-2"></i>Remove</a></li>
                        <li><a href="javascript:void(0);"><i class="feather feather-settings me-2"></i>More</a></li> --}}
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
        @endisset
    </div>
</div>
<!--/sidebar-right-->

<!--Clock-IN Modal -->
<div class="modal fade" id="clockinmodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="feather feather-clock  me-1"></span>Clock In</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="countdowntimer"><span id="clocktimer" class="border-0"></span></div>
                <div class="form-group">
                    <label class="form-label">Note:</label>
                    <textarea class="form-control" rows="3">Some text here...</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary">Clock In</button>
            </div>
        </div>
    </div>
</div>
<!-- End Clock-IN Modal  -->

<!--Change password Modal -->
<!-- End Change password Modal  -->