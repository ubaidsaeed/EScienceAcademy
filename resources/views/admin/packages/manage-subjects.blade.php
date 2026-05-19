@extends('layouts.admin.app')

@push('style')
    <style>
        .bg-light-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4, #825ee4);
        }
    </style>
@endpush
@section('content')

    <div class="app-content main-content">
        <div class="side-app">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Subjects for: {{ $package->name }}</h1>
                    <div>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Packages
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Package Subjects</h6>
                            </div>
                            <div class="card-body">
                                @if ($package->subjects->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Subject Name</th>
                                                    <th width="280">Price in Package</th>
                                                    <th width="100">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalSubjectPrice = 0;
                                                @endphp

                                                @foreach ($package->subjects as $subject)
                                                    @php $totalSubjectPrice += $subject->pivot->subject_price; @endphp
                                                    <tr id="subject-row-{{ $subject->id }}">
                                                        <td class="align-middle">
                                                            <strong>{{ $subject->name }}</strong>
                                                        </td>
                                                        <td>
                                                            <form id="price-form-{{ $subject->id }}" class="d-flex">
                                                                @csrf
                                                                <div class="input-group">
                                                                    <span class="input-group-text">PKR</span>
                                                                    <input type="number" class="form-control subject-price"
                                                                        value="{{ $subject->pivot->subject_price }}"
                                                                        step="0.01" min="0"
                                                                        data-subject-id="{{ $subject->id }}"
                                                                        data-original="{{ $subject->pivot->subject_price }}">
                                                                        @can('edit package subject')
                                                                    <button type="button"
                                                                        class="btn btn-primary update-price btn-sm"
                                                                        data-subject-id="{{ $subject->id }}">
                                                                        Update
                                                                    </button>
                                                                    @endcan
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @can('delete package subject')
                                                            <button class="btn btn-danger btn-sm remove-subject"
                                                                data-subject-id="{{ $subject->id }}">
                                                                Remove
                                                            </button>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            <!-- TOTALS FOOTER -->
                                            {{-- <tfoot>
                                                <tr class="table-success">
                                                    <td><strong>Total Subjects</strong></td>
                                                    <td colspan="2" class="text-end">
                                                        <strong>{{ $package->subjects->count() }} subject(s)</strong>
                                                    </td>
                                                </tr>
                                                <tr class="table-info">
                                                    <td><strong>Sum of Subject Prices</strong></td>
                                                    <td colspan="2" class="text-end">
                                                        <strong>PKR {{ number_format($totalSubjectPrice, 2) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>Package Price</strong></td>
                                                    <td colspan="2" class="text-end">
                                                        <strong>PKR {{ number_format($package->price, 2) }}</strong>
                                                    </td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td><strong>Final Price (Students Pay)</strong></td>
                                                    <td colspan="2" class="text-end">
                                                        <strong class="h5 text-white bg-primary px-3 py-2 rounded">
                                                            PKR
                                                            {{ number_format($package->price + $totalSubjectPrice, 2) }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tfoot> --}}
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        No subjects added to this package yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add Subject</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.packages.add-subject', $package->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="subject_id">Select Subject</label>
                                        <select name="subject_id" id="subject_id" class="form-control" required>
                                            <option value="">Select Subject</option>
                                            @foreach ($availableSubjects as $subject)
                                                <option value="{{ $subject->id }}">
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_price">Subject Price in Package</label>
                                        <input type="number" name="subject_price" id="subject_price" class="form-control"
                                            step="0.01" min="0" required>
                                        <small class="text-muted">This is the price for this subject within the
                                            package</small>
                                    </div>
                                    @can('create package subject')
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-plus"></i> Add to Package
                                    </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                        {{-- <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-info text-white">
                            <h6 class="m-0 font-weight-bold">Savings Calculator</h6>
                        </div>
                        <div class="card-body">
                            <button id="calculate-savings" class="btn btn-info btn-block">
                                <i class="fas fa-calculator"></i> Calculate Savings
                            </button>
                            <div id="savings-result" class="mt-3" style="display: none;">
                                <div class="alert alert-success">
                                    <h6>Savings Analysis</h6>
                                    <p><strong>Individual Total:</strong> ₹<span id="individual-total">0.00</span></p>
                                    <p><strong>Package Price:</strong> ₹<span
                                            id="package-price">{{ number_format($package->price, 2) }}</span></p>
                                    <p><strong>Total Savings:</strong> ₹<span id="total-savings">0.00</span></p>
                                    <p><strong>Savings Percentage:</strong> <span id="savings-percentage">0.00</span>%
                                    </p>
                                    <p><strong>Subjects Included:</strong> <span id="subject-count">0</span></p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Update subject price
            $(document).on('click', '.update-price', function() {
                var subjectId = $(this).data('subject-id');
                var price = $('#price-form-' + subjectId + ' .subject-price').val();

                if (price <= 0) {
                    toastr.error('Please enter a valid price');
                    return;
                }

                $.ajax({
                    url: '/admin/packages/{{ $package->id }}/subjects/' + subjectId +
                        '/update-price',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        subject_price: price
                    },
                    success: function(response) {
                        setTimeout(() => {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success",
                                title: response.success
                            });
                        }, 500);
                    },
                    error: function() {
                        setTimeout(() => {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "error",
                                title: 'Error updating price'
                            });
                        }, 500);
                    }
                });
            });

            // Remove subject
            $(document).on('click', '.remove-subject', function() {
                var subjectId = $(this).data('subject-id');

                if (confirm('Are you sure you want to remove this subject from the package?')) {
                    $.ajax({
                        url: '/admin/packages/{{ $package->id }}/subjects/' + subjectId +
                            '/remove',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            $('#subject-row-' + subjectId).remove();
                            location.reload(); // Reload to update available subjects
                        },
                        error: function() {
                            toastr.error('Error removing subject');
                        }
                    });
                }
            });

            // Calculate savings
            $('#calculate-savings').click(function() {
                $.ajax({
                    url: '/admin/packages/{{ $package->id }}/calculate-savings',
                    type: 'GET',
                    success: function(response) {
                        $('#individual-total').text(response.individual_total);
                        $('#total-savings').text(response.savings);
                        $('#savings-percentage').text(response.savings_percentage);
                        $('#subject-count').text(response.subject_count);
                        $('#savings-result').show();
                    }
                });
            });
        });
    </script>
@endpush
