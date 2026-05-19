<div>
    @if ($page_type != 'view')
    @endif
    <div class="">

        <!-- Row -->
        <div class="page-header d-lg-flex d-block">

            <div class="page-leftheader">
                <h4 class="page-title">Subscribe's</h4>
            </div>
            <div class="page-rightheader ">
                @if (session('message'))
                    <div class="alert alert-success alert-message fade show" role="alert" id="success-alert">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                        {{ session('error') }}
                    </div>
                @endif
                <div class=" btn-list">
                   @if ($page_type == 'Assignment')
                    <button type="button" class="btn btn-secondary" wire:click="back">
                        Back
                    </button>
                @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!--div-->
                @if ($page_type == 'View')
                    <div class="card">
                        <div class="card-body">
                            <div id="file-export_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <livewire:subscribe-table />
                            </div>
                        </div>
                    </div>
                    @elseif($page_type == 'Assignment')
                    <div class="row">
                        @if ($userAssignment->count() > 0)
                            @foreach ($userAssignment as $index => $item)
                                <div class="col-lg-6 col-sm-6">
                                    <div class="card" style="width: 18rem;">
                                        {{-- <img src="{{asset('storage/app/submission_assignment/'.$item->file)}}" class="card-img-top" alt="Submission Image"> --}}
                                        @php
                                            $filePath = 'storage/submission_assignment/' . $item->file;
                                            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <!-- Show Image -->
                                            <img src="{{ asset($filePath) }}" class="card-img-top"
                                                alt="Submission Image" width="200">
                                        @elseif(strtolower($extension) == 'pdf')
                                            <!-- Show PDF -->
                                            <embed src="{{ asset($filePath) }}" type="application/pdf" width="100%"
                                                height="500px" />
                                        @else
                                            <p>File format not supported</p>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $item->title }}</h5>
                                            <p class="card-text">{{ $item->chapter }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- show empty message --}}
                            <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                                No data found
                            </div>
                        @endif
                    </div>
                @elseif($page_type == 'invoice')
                    <style>
                        .invoice-container {
                            padding: 1rem;
                        }

                        .invoice-container .invoice-header .invoice-logo {
                            margin: 0.8rem 0 0 0;
                            display: inline-block;
                            font-size: 1.6rem;
                            font-weight: 700;
                            color: #2e323c;
                        }

                        .invoice-container .invoice-header .invoice-logo img {
                            max-width: 130px;
                        }

                        .invoice-container .invoice-header address {
                            font-size: 0.8rem;
                            color: #9fa8b9;
                            margin: 0;
                        }

                        .invoice-container .invoice-details {
                            margin: 1rem 0 0 0;
                            padding: 1rem;
                            line-height: 180%;
                            background: #f5f6fa;
                        }

                        .invoice-container .invoice-details .invoice-num {
                            text-align: right;
                            font-size: 0.8rem;
                        }

                        .invoice-container .invoice-body {
                            padding: 1rem 0 0 0;
                        }

                        .invoice-container .invoice-footer {
                            text-align: center;
                            font-size: 0.7rem;
                            margin: 5px 0 0 0;
                        }

                        .invoice-status {
                            text-align: center;
                            padding: 1rem;
                            background: #ffffff;
                            -webkit-border-radius: 4px;
                            -moz-border-radius: 4px;
                            border-radius: 4px;
                            margin-bottom: 1rem;
                        }

                        .invoice-status h2.status {
                            margin: 0 0 0.8rem 0;
                        }

                        .invoice-status h5.status-title {
                            margin: 0 0 0.8rem 0;
                            color: #9fa8b9;
                        }

                        .invoice-status p.status-type {
                            margin: 0.5rem 0 0 0;
                            padding: 0;
                            line-height: 150%;
                        }

                        .invoice-status i {
                            font-size: 1.5rem;
                            margin: 0 0 1rem 0;
                            display: inline-block;
                            padding: 1rem;
                            background: #f5f6fa;
                            -webkit-border-radius: 50px;
                            -moz-border-radius: 50px;
                            border-radius: 50px;
                        }

                        .invoice-status .badge {
                            text-transform: uppercase;
                        }

                        @media (max-width: 767px) {
                            .invoice-container {
                                padding: 1rem;
                            }
                        }


                        .custom-table {
                            border: 1px solid #e0e3ec;
                        }

                        .custom-table thead {
                            background: #007ae1;
                        }

                        .custom-table thead th {
                            border: 0;
                            color: #ffffff;
                        }

                        .custom-table>tbody tr:hover {
                            background: #fafafa;
                        }

                        .custom-table>tbody tr:nth-of-type(even) {
                            background-color: #ffffff;
                        }

                        .custom-table>tbody td {
                            border: 1px solid #e6e9f0;
                        }


                        .card {
                            background: #ffffff;
                            -webkit-border-radius: 5px;
                            -moz-border-radius: 5px;
                            border-radius: 5px;
                            border: 0;
                            margin-bottom: 1rem;
                        }

                        .text-success {
                            color: #00bb42 !important;
                        }

                        .text-muted {
                            color: #9fa8b9 !important;
                        }

                        .custom-actions-btns {
                            margin: auto;
                            display: flex;
                            justify-content: flex-end;
                        }

                        .custom-actions-btns .btn {
                            margin: .3rem 0 .3rem .3rem;
                        }
                    </style>
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="invoice-container">
                                                    <div class="invoice-header">
                                                        <!-- Row start -->
                                                        {{-- <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div class="custom-actions-btns mb-5">
                                                        <a href="#" class="btn btn-primary">
                                                            <i class="icon-download"></i> Download
                                                        </a>
                                                        <a href="#" class="btn btn-secondary">
                                                            <i class="icon-printer"></i> Print
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> --}}
                                                        <!-- Row end -->
                                                        <!-- Row start -->
                                                        <div class="row gutters">
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                                <a href="index.html" class="invoice-logo">
                                                                    eScience Academy
                                                                </a>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                {{-- <address class="text-right">
                                                        Maxwell admin Inc, 45 NorthWest Street.<br>
                                                        Sunrise Blvd, San Francisco.<br>
                                                        00000 00000
                                                    </address> --}}
                                                            </div>
                                                        </div>
                                                        <!-- Row end -->
                                                        <!-- Row start -->
                                                        <div class="row gutters">
                                                            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                                                <div class="invoice-details">
                                                                    <address>
                                                                        {{ $main_parent_name['subscription']->user_name }}<br>
                                                                        {{ $main_parent_name['subscription']->user_email }}
                                                                    </address>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                                               
                                                            </div>
                                                        </div>
                                                        <!-- Row end -->
                                                    </div>
                                                    <div class="invoice-body">
                                                        <!-- Row start -->
                                                        <form wire:submit.prevent="updateSubscription" method="post">
                                                            @csrf
                                                        <div class="row gutters">
                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                <div class="table-responsive">
                                                                    
                                                                        <input type="hidden" wire:model="subscription_id" value="{{ $main_parent_name['subscription']->id }}">
                                                                    <table class="table custom-table m-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Package Name</th>
                                                                                <th>Price</th>
                                                                                <th>Duration</th>
                                                                                <th>Start Date</th>
                                                                                <th>End Date</th>
                                                                                {{-- <th>Payment Method</th> --}}
                                                                                <th>Payment Status</th>
                                                                                <th>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $main_parent_name['plan']->name}}
                                                                                </td>

                                                                                <td>{{ $main_parent_name['plan']->price }}
                                                                                </td>
                                                                                <td>{{ $main_parent_name['plan']->duration}} {{ $main_parent_name['plan']->duration_type}}</td>
                                                                                <td>{{ $main_parent_name['subscription']->start_date }}</td>
                                                                                <td>{{ $main_parent_name['subscription']->end_date }}</td>
                                                                                {{-- <td>{{ $main_parent_name['subscription']->payment_method_id }}</td> --}}
                                                                                
                                                                                <td>
                                                                                    <div x-data x-init="$nextTick(() => {
                                                                                        let statusSelects = $refs.payment_status;
                                                                                        $(statusSelects).select2();
                                                    
                                                                                        // Capture Select2 change and manually sync to Livewire
                                                                                        $(statusSelects).on('select2:select', function (e) {
                                                                                            // Directly set the group_id value in Livewire
                                                                                            $wire.set('payment_status', $(this).val());
                                                                                        });
                                                    
                                                                                        // Sync the Select2 component with Livewire changes
                                                                                        $watch('payment_status', value => {
                                                                                            $(statusSelects).val(value).trigger('change.select2');
                                                                                        });
                                                                                    })" wire:ignore>
                                                                                    <select class="form-control select2-show-search custom-select" x-ref="payment_status" wire:model="payment_status" id="payment_status">
                                                                                        <option value="pending" @if($main_parent_name['subscription']->payment_status == 'pending') selected @endif>Pending</option>
                                                                                        <option value="paid" @if($main_parent_name['subscription']->payment_status == 'paid') selected @endif>Paid</option>
                                                                                        <option value="cancelled" @if($main_parent_name['subscription']->payment_status == 'cancelled') selected @endif>Cancelled</option>
                                                                                    </select>
                                                                                    </div>
                                                                                    {{-- {{ $main_parent_name['subscription']->payment_status }} --}}
                                                                                </td>
                                                                                <td>
                                                                                    <div x-data x-init="$nextTick(() => {
                                                                                        let statusSelect = $refs.status;
                                                                                        $(statusSelect).select2();
                                                    
                                                                                        // Capture Select2 change and manually sync to Livewire
                                                                                        $(statusSelect).on('select2:select', function (e) {
                                                                                            // Directly set the group_id value in Livewire
                                                                                            $wire.set('status', $(this).val());
                                                                                        });
                                                    
                                                                                        // Sync the Select2 component with Livewire changes
                                                                                        $watch('status', value => {
                                                                                            $(statusSelect).val(value).trigger('change.select2');
                                                                                        });
                                                                                    })" wire:ignore>
                                                                                    <select class="form-control select2-show-search custom-select" x-ref="status" wire:model="status" id="status">
                                                                                        {{-- <option value="" selected disabled="disabled">Choose....</option> --}}
                                                                                        <option value="active" >Active</option>
                                                                                        <option value="unactive">Unactive</option>
                                                                                    </select>
                                                                                </div>
                                                                                    {{-- {{ $main_parent_name['subscription']->status }} --}}
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Row end -->
                                                    </div>
                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" wire:click="back" class="btn btn-secondary">Close</button>
                                <button type="button" wire:click="updateSubscription"
                                    class="btn btn-primary shadow-none">{{ $button }}
                                </button>
                            </div>
                        </form>
                @endif

                <!--div-->
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
    @endpush
</div>
