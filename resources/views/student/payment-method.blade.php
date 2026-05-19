@extends('layouts.admin.app')
@section('title', 'payment-method')
@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    .package-container {
        display: flex;
        max-width: 1400px;
        width: 100%;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        min-height: 700px;
        margin-bottom: 30px;
    }

    /* Left Section - Package Details */
    .package-details {
        flex: 1;
        background: linear-gradient(145deg, #2c3e50, #34495e);
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .package-details::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .package-details::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
    }

    .package-title {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        position: relative;
        z-index: 2;
    }
    .include-title {
       background: white;
      padding: 5px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s;
      border: 2px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
        border-color: #0072ff;
      background: rgba(0, 114, 255, 0.05);
      width: 380px;
    }

    .package-subtitle {
        font-size: 1.2rem;
        color: #bdc3c7;
        margin-bottom: 40px;
        position: relative;
        z-index: 2;
    }

    .detail-item {
        margin-bottom: 30px;
        position: relative;
        z-index: 2;
    }

    .detail-label {
        font-size: 1rem;
        color: #95a5a6;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
    }

    .highlight-value {
        color: #00c6ff;
        font-size: 2.2rem;
    }

    .detail-icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        text-align: center;
        line-height: 40px;
        margin-right: 15px;
        font-size: 1.2rem;
    }

    /* Center Section - Features */
    .package-features {
        flex: 1.2;
        padding: 40px;
        background: #f8f9fa;
        border-right: 1px solid #eaeaea;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #0072ff;
        display: inline-block;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 50px;
    }

    .feature-item {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        align-items: center;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0072ff, #00c6ff);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 1.3rem;
    }

    .feature-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .subjects-section {
        margin-top: 40px;
    }

    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .subject-item {
        background: white;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .subject-item:hover {
        border-color: #0072ff;
        transform: translateY(-3px);
    }

    .subject-item.selected {
        border-color: #0072ff;
        background: rgba(0, 114, 255, 0.05);
    }

    .subject-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2c3e50, #34495e);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-weight: bold;
    }

    .subject-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        flex-grow: 1;
    }

    .subject-price-tag {
        margin-left: auto;
        font-weight: 600;
        color: #0072ff;
        font-size: 1rem;
        background: rgba(0, 114, 255, 0.1);
        padding: 5px 10px;
        border-radius: 8px;
    }

    /* Right Section - Price & Checkout */
    .package-pricing {
        flex: 0.9;
        padding: 40px;
        background: white;
        display: flex;
        flex-direction: column;
    }

    .price-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .total-price {
        font-size: 3.5rem;
        font-weight: 800;
        color: #0072ff;
        margin-bottom: 10px;
    }

    .price-label {
        font-size: 1.2rem;
        color: #7f8c8d;
    }

    .duration-selector {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }

    .duration-label {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .duration-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .duration-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0072ff, #00c6ff);
        color: white;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    .duration-btn:hover {
        transform: scale(1.1);
    }

    .duration-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        min-width: 100px;
    }

    .price-breakdown {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        flex-grow: 1;
    }

    .breakdown-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0072ff;
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #eaeaea;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .item-price {
        font-size: 1.0rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .breakdown-total {
        display: flex;
        justify-content: space-between;
        padding: 20px 0 0;
        margin-top: 20px;
        border-top: 2px solid #0072ff;
        font-weight: 700;
        
        font-size: 1.0rem;
        color: #2c3e50;
    }

    .cta-button {
        background: linear-gradient(135deg, #0072ff, #00c6ff);
        color: white;
        border: none;
        padding: 20px;
        border-radius: 15px;
        font-size: 1.3rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 114, 255, 0.4);
    }

    /* Plan Selector */
    .plan-selector {
        margin-bottom: 30px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 15px;
    }

    .plan-selector label {
        font-size: large;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: block;
    }

    .plan-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        background: white;
        transition: border-color 0.3s;
    }

    .plan-select:focus {
        outline: none;
        border-color: #0072ff;
    }

    .plan-option-group {
        font-weight: 600;
        color: #2c3e50;
        background: #e9ecef;
    }

    .plan-option {
        padding-left: 30px;
    }

    .loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 100;
        justify-content: center;
        align-items: center;
        border-radius: 24px;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #0072ff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error-message {
        background: #ffeaea;
        color: #d32f2f;
        padding: 15px;
        border-radius: 10px;
        margin: 20px 0;
        display: none;
    }

    .success-message {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 15px;
        border-radius: 10px;
        margin: 20px 0;
        display: none;
    }

    .plan-info {
        background: #e3f2fd;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .plan-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0072ff;
    }

    /* Payment Section */
    .payment-section {
        margin-top: 40px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 15px;
    }

    .payment-section h3 {
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0072ff;
    }

    /* Grand Total Section */
    .grand-total {
        background: linear-gradient(135deg, #0072ff, #00c6ff);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-top: 20px;
        text-align: center;
    }

    .grand-total-label {
        font-size: 1.2rem;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .grand-total-amount {
        font-size: 2.5rem;
        font-weight: 800;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .package-container {
            flex-direction: column;
            max-width: 800px;
        }
        
        .package-details, .package-features, .package-pricing {
            border-right: none;
            border-bottom: 1px solid #eaeaea;
        }
        
        .features-grid, .subjects-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .package-title {
            font-size: 2.2rem;
        }
        
        .total-price {
            font-size: 2.8rem;
        }
        
        .package-details, .package-features, .package-pricing {
            padding: 30px;
        }
        
        .subject-item {
            flex-direction: column;
            text-align: center;
        }
        
        .subject-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
        
        .subject-price-tag {
            margin-left: 0;
            margin-top: 10px;
        }
    }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="app-content main-content">
    <div class="side-app">
        @if($existingSubscription)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> You have an existing subscription. You can renew or upgrade it.
        </div>
        @endif
        
        <div class="plan-selector">
            <label for="planSelect"><i class="fas fa-box"></i> Select Subscription Plan</label>
            <select id="planSelect" class="plan-select">
                <option value="">-- Choose a Plan --</option>
                @foreach($boards as $board)
                    @php
                        $boardPlans = $subscriptionPlans->where('board_id', $board->id);
                    @endphp
                    @if($boardPlans->count() > 0)
                        <optgroup label="{{ $board->name }}">
                            @foreach($boardPlans as $plan)
                                @php
                                    $levelName = $plan->level_name ?? 'All Levels';
                                @endphp
                                <option value="{{ $plan->id }}" data-price="{{ $plan->plan_price }}">
                                    {{ $plan->name }} - {{ $levelName }} (PKR {{ number_format($plan->plan_price) }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            </select>
        </div>

        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>

        <div class="package-container my-2" id="packageContainer" style="display: ;">
            <div class="loading-overlay" id="loadingOverlay">
                <div class="spinner"></div>
            </div>
            
            <!-- Left Section: Package Details -->
            <div class="package-details">
                <h1 class="package-title" id="planName">Mastermind Plan</h1>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <span class="detail-icon"><i class="fas fa-university"></i></span>
                        Board
                    </div>
                    <div class="detail-value" id="boardName">CAIE A-Level</div>
                </div>
                
                <div class="detail-item d-none">
                    <div class="detail-label">
                        <span class="detail-icon"><i class="fas fa-tag"></i></span>
                        Plan Price
                    </div>
                    <div class="detail-value highlight-value" id="planPrice">PKR 0</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <span class="detail-icon"><i class="fas fa-calendar-alt"></i></span>
                        Base Duration
                    </div>
                    <div class="detail-value" id="baseDuration">1 Month</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">
                        <span class="detail-icon"><i class="fas fa-graduation-cap"></i></span>
                        Education Level
                    </div>
                    <div class="detail-value" id="levelName">A-Level Program</div>
                </div>
                
                <div class="detail-item d-none">
                    <div class="detail-label">
                        <span class="detail-icon"><i class="fas fa-star"></i></span>
                        Plan Type
                    </div>
                    <div class="detail-value" id="planType">Premium Plan</div>
                </div>
            </div>
            
            <!-- Center Section: Features & Subjects -->
            <div class="package-features">
                <h2 class="section-title">Package Features</h2>
                <div class="features-grid" id="featuresGrid">
                    <!-- Dynamic features will be loaded here -->
                </div>
                
                <div class="subjects-section">
                    <h2 class="section-title">Select Subjects</h2>
                    <div class="subjects-grid" id="subjectsGrid">
                        <!-- Dynamic subjects will be loaded here -->
                    </div>
                </div>
                <h2 class="section-title"></h2>
                <div class="features-grid" id="featuresGrid">
                   <p class="include-title" id="planName"><i class="fa-solid fa-circle-info" style="margin-top: -55px !important; "></i>The first subject amount is already included in the package price. The remaining subject will be charged as per the selection.</p>
                </div>
            </div>
            
            <!-- Right Section: Pricing & Checkout -->
            <div class="package-pricing">
                <div class="price-header">
                    <div class="total-price" id="totalPrice">PKR 0</div>
                    <div class="price-label">Total Package Price (Plan Only)</div>
                </div>
                
                <div class="duration-selector">
                    <div class="duration-label">Select Duration</div>
                    <div class="duration-control">
                        <button class="duration-btn" id="decreaseDuration">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="duration-value">
                            <span id="durationValue">1</span> Month<span id="durationPlural"></span>
                        </div>
                        <button class="duration-btn" id="increaseDuration">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div style="margin-top: 10px; color: #7f8c8d; font-size: 0.9rem;">
                        <i class="fas fa-info-circle"></i> Increase duration for extended access
                    </div>
                </div>
                
                <div class="price-breakdown">
                    <h3 class="breakdown-title">Price Breakdown</h3>
                    <div class="breakdown-list" id="breakdownList">
                        <!-- Dynamic content will be added here -->
                    </div>
                     @isset($activeSubscriptionDetails)
                    <div>
                        <div class="breakdown-total d-none" style="border-top: 2px solid #0072ff;font-size: 1.0rem;">
                            <span>Plan Total:</span>
                            <span id="planTotalAmount">PKR 0</span>
                        </div>
                        <div class="breakdown-total d-none" style="border-top: none;">
                            <span>Subjects Total:</span>
                            <span id="subjectsTotalAmount">PKR 0</span>
                        </div>
                        <div class="breakdown-total" style="border-top: 2px solid #0072ff; font-size: 1.2rem;">
                            <span>Grand Total:</span>
                            <span id="grandTotalAmount">PKR 0</span>
                        </div>
                    </div>
                    @else
                    <div id="grandTotalSection" style="display: none;">
                        <div class="breakdown-total d-none" style="border-top: 2px solid #0072ff;font-size: 1.0rem;">
                            <span>Plan Total:</span>
                            <span id="planTotalAmount">PKR 0</span>
                        </div>
                        <div class="breakdown-total d-none" style="border-top: none;">
                            <span>Subjects Total:</span>
                            <span id="subjectsTotalAmount">PKR 0</span>
                        </div>
                        <div class="breakdown-total" style="border-top: 2px solid #0072ff; font-size: 1.2rem;">
                            <span>Grand Total:</span>
                            <span id="grandTotalAmount">PKR 0</span>
                        </div>
                    </div>
                </div>
                
                <div class="grand-total d-none" id="grandTotalDisplay" style="display: none;">
                    <div class="grand-total-label">Final Amount to Pay</div>
                    <div class="grand-total-amount" id="grandTotalDisplayAmount">PKR 0</div>
                </div>
                    @endisset

                    <!-- Grand Total Section -->
                    <!--<div id="grandTotalSection" style="display: none;">-->
                    <!--    <div class="breakdown-total" style="border-top: 2px solid #0072ff;font-size: 1.0rem;">-->
                    <!--        <span>Plan Total:</span>-->
                    <!--        <span id="planTotalAmount">PKR 0</span>-->
                    <!--    </div>-->
                    <!--    <div class="breakdown-total" style="border-top: none;">-->
                    <!--        <span>Subjects Total:</span>-->
                    <!--        <span id="subjectsTotalAmount">PKR 0</span>-->
                    <!--    </div>-->
                    <!--    <div class="breakdown-total" style="border-top: 2px solid #0072ff; font-size: 1.2rem;">-->
                    <!--        <span>Grand Total:</span>-->
                    <!--        <span id="grandTotalAmount">PKR 0</span>-->
                    <!--    </div>-->
                    <!--</div>-->
                <!--</div>-->
                
                <div class="grand-total d-none" id="grandTotalDisplay" style="display: none;">
                    <div class="grand-total-label">Final Amount to Pay</div>
                    <div class="grand-total-amount" id="grandTotalDisplayAmount">PKR 0</div>
                </div>
                
                <button class="cta-button" id="checkoutBtn">
                    <i class="fas fa-shopping-cart"></i>
                    Proceed to Checkout
                </button>
                </div>
            </div>
        </div>
        
        <!-- Payment Information Section (Initially Hidden) -->
        <div id="paymentSection" style="display: none;">
            @include('student.partials.payment-section')
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Global variables
let currentPlanId = null;
let selectedSubjects = [];
let basePrice = 0;
let duration = 1;
let maxDuration = 12;
let currentSubscriptionId = null;
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let isEditMode = false;
let existingSubscriptionSubjects = [];

// Check if we're in edit mode
@if(isset($activeSubscriptionDetails) && isset($activeSubscriptionSubjects))
    isEditMode = true;
    existingSubscriptionSubjects = @json($activeSubscriptionSubjects ?: []);
@endif

// DOM Elements
const planSelect = document.getElementById('planSelect');
const packageContainer = document.getElementById('packageContainer');
const loadingOverlay = document.getElementById('loadingOverlay');
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');
const paymentSection = document.getElementById('paymentSection');

// Left panel elements
const planName = document.getElementById('planName');
const boardName = document.getElementById('boardName');
const planPrice = document.getElementById('planPrice');
const baseDuration = document.getElementById('baseDuration');
const levelName = document.getElementById('levelName');
const planType = document.getElementById('planType');

// Center panel elements
const featuresGrid = document.getElementById('featuresGrid');
const subjectsGrid = document.getElementById('subjectsGrid');

// Right panel elements
const totalPrice = document.getElementById('totalPrice');
const durationValue = document.getElementById('durationValue');
const durationPlural = document.getElementById('durationPlural');
const breakdownList = document.getElementById('breakdownList');
const grandTotalSection = document.getElementById('grandTotalSection');
const planTotalAmount = document.getElementById('planTotalAmount');
const subjectsTotalAmount = document.getElementById('subjectsTotalAmount');
const grandTotalAmount = document.getElementById('grandTotalAmount');
const grandTotalDisplay = document.getElementById('grandTotalDisplay');
const grandTotalDisplayAmount = document.getElementById('grandTotalDisplayAmount');
const decreaseBtn = document.getElementById('decreaseDuration');
const increaseBtn = document.getElementById('increaseDuration');
const checkoutBtn = document.getElementById('checkoutBtn');

// Plan selection handler
planSelect.addEventListener('change', function() {
    const planId = this.value;
    
    if (!planId) {
        paymentSection.style.display = 'none';
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';
        return;
    }
    
    loadPlanDetails(planId);
});

// Load plan details from server
async function loadPlanDetails(planId) {
    try {
        showLoading();
        packageContainer.style.display = 'flex';
        paymentSection.style.display = 'none';
        errorMessage.style.display = 'none';
        
        const response = await fetch(`/student/payment-method/plan-details/${planId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentPlanId = planId;
            updatePlanDetails(data);
            updatePlanPriceDisplay();
            resetPriceBreakdown();
            
            // If in edit mode and this is the current plan, pre-select existing subjects
            if (isEditMode && planId == "{{ $existingSubscription->plan_id ?? '' }}") {
                preSelectExistingSubjects(data.subjects);
            }
        } else {
            showError(data.message || 'Failed to load plan details');
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        hideLoading();
    }
}

// Update plan details in UI
function updatePlanDetails(data) {
    const plan = data.plan;
    const board = data.board;
    const level = data.level;
    const features = data.features;
    const featureRecords = data.featureRecords;
    const subjects = data.subjects;
   
    // Update left panel
    planName.textContent = plan.name;
    boardName.textContent = board ? board.name : 'Not specified';
    baseDuration.textContent = `${plan.duration} ${plan.duration_type}`;
    levelName.textContent = level ? level.name : 'All Levels';
    planType.textContent = plan.slug ? plan.slug.replace('-', ' ').toUpperCase() : 'Premium Plan';
    
    basePrice = parseFloat(plan.price);
    // Update features
    updateFeatures(features,featureRecords);
    
    // Update subjects
    updateSubjects(subjects);
}

// Pre-select existing subjects for edit mode
function preSelectExistingSubjects(subjects) {
    if (!existingSubscriptionSubjects || existingSubscriptionSubjects.length === 0) {
        return;
    }
    
    console.log('Pre-selecting existing subjects:', existingSubscriptionSubjects);
    
    // Clear any existing selections first
    selectedSubjects = [];
    
    // Create a map of existing subject IDs to their data
    const existingSubjectsMap = {};
    existingSubscriptionSubjects.forEach(subject => {
        existingSubjectsMap[subject.id] = {
            id: subject.id,
            free_subject_id: subject.free_subject_id,
            name: subject.name,
            subject_price: subject.subject_price
        };
    });
    
    // Select each existing subject
    subjects.forEach(subject => {
        const subjectItem = document.querySelector(`.subject-item[data-subject-id="${subject.id}"]`);
        if (subjectItem && existingSubjectsMap[subject.id]) {
            const existingSubject = existingSubjectsMap[subject.id];
            const isFreeSubject = existingSubject.free_subject_id == subject.id;
            
            selectedSubjects.push({
                id: subject.id,
                name: subject.name,
                price: isFreeSubject ? 0 : parseFloat(subject.subject_price),
                originalPrice: parseFloat(subject.subject_price),
                isExisting: true,
                wasFreeInExisting: isFreeSubject,
                freeSubjectId: isFreeSubject ? subject.id : null
            });
            
            subjectItem.classList.add('selected');
            
            // Add price tag
            const priceTag = document.createElement('span');
            priceTag.className = 'subject-price-tag';
            
            if (isFreeSubject) {
                // priceTag.textContent = 'FREE';
                // priceTag.style.color = '#28a745';
                // priceTag.style.fontWeight = 'bold';
                // priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
            } else {
                // priceTag.textContent = `PKR ${subject.subject_price}`;
            }
            
            subjectItem.appendChild(priceTag);
            
            // Add "Current" badge
            const currentBadge = document.createElement('span');
            currentBadge.className = 'badge bg-warning ms-2';
            currentBadge.textContent = 'Current';
            currentBadge.style.fontSize = '0.7rem';
            currentBadge.style.padding = '3px 6px';
            subjectItem.querySelector('.subject-name').appendChild(currentBadge);
        }
    });
    
    // Update breakdown with existing selections
    updatePriceBreakdown();
    
    // Update checkout button text for edit mode
    checkoutBtn.innerHTML = `
        <i class="fas fa-sync-alt"></i>
        Update Package
    `;
    
    // Show edit mode message
    showSuccess(`Editing existing package with ${selectedSubjects.length} subjects.`);
}

// Update plan price display
function updatePlanPriceDisplay() {
    const planPriceValue = basePrice * duration;
    totalPrice.textContent = `PKR ${parseInt(planPriceValue).toLocaleString()}`;
    planPrice.textContent = `PKR ${parseInt(basePrice).toLocaleString()}`;
}

// Reset price breakdown
function resetPriceBreakdown() {
    breakdownList.innerHTML = '<div style="text-align: center; color: #7f8c8d; padding: 20px;">Select subjects to see breakdown</div>';
    
    grandTotalSection.style.display = 'none';
    grandTotalDisplay.style.display = 'none';
    
    // Reset selected subjects
    selectedSubjects = [];
    
    // Update subject selection UI
    const subjectItems = document.querySelectorAll('.subject-item');
    subjectItems.forEach(item => {
        item.classList.remove('selected');
        // Remove any price tags
        const priceTag = item.querySelector('.subject-price-tag');
        if (priceTag) {
            priceTag.remove();
        }
        // Remove any badges
        const badges = item.querySelectorAll('.badge');
        badges.forEach(badge => badge.remove());
    });
    
    // Reset checkout button if not in edit mode
    if (!isEditMode) {
        checkoutBtn.innerHTML = `
            <i class="fas fa-shopping-cart"></i>
            Proceed to Checkout
        `;
    }
}

// Update features grid
function updateFeatures(features, featureRecords) {
    featuresGrid.innerHTML = '';

    if (!featureRecords || featureRecords.length === 0) {
        featuresGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #7f8c8d;">
                <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 20px;"></i>
                <p>No features available</p>
            </div>
        `;
        return;
    }

    const activeFeatureIds = features ? features.map(f => f.id) : [];

    featureRecords.forEach(feature => {
        const featureItem = document.createElement('div');
        featureItem.className = 'feature-item';

        const isAvailable = activeFeatureIds.includes(feature.id);
        const iconClass = isAvailable ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';

        featureItem.innerHTML = `
            <div class="feature-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="feature-text">${feature.name}</div>
        `;

        featuresGrid.appendChild(featureItem);
    });
}

// Update subjects grid
// function updateSubjects(subjects) {
//     subjectsGrid.innerHTML = '';
//     selectedSubjects = [];
    
//     if (!subjects || subjects.length === 0) {
//         subjectsGrid.innerHTML = `
//             <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #7f8c8d;">
//                 <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 20px;"></i>
//                 <p>No subjects available for this plan</p>
//             </div>
//         `;
//         return;
//     }
    
//     // Get existing subscription subjects data
//     const existingSubjects = @json($activeSubscriptionSubjects ?: []);
    
//     // Create a map of existing subject IDs to their data
//     const existingSubjectsMap = {};
//     existingSubjects.forEach(subject => {
//         existingSubjectsMap[subject.id] = {
//             id: subject.id,
//             free_subject_id: subject.free_subject_id,
//             name: subject.name,
//             subject_price: subject.subject_price
//         };
//     });
    
//     subjects.forEach(subject => {
//         const subjectItem = document.createElement('div');
//         subjectItem.className = 'subject-item';
//         subjectItem.dataset.subjectId = subject.id;
//         subjectItem.dataset.subjectName = subject.name;
//         subjectItem.dataset.subjectPrice = subject.subject_price;
        
//         const firstLetter = subject.name.charAt(0).toUpperCase();
        
//         subjectItem.innerHTML = `
//             <div class="subject-icon">${firstLetter}</div>
//             <div class="subject-name">${subject.name}</div>
//         `;
        
//         // Check if this subject exists in the current subscription
//         const existingSubject = existingSubjectsMap[subject.id];
        
//         // Add "Current" badge if it's an existing subject
//         if (existingSubject) {
//             const currentBadge = document.createElement('span');
//             currentBadge.className = 'badge bg-warning ms-2';
//             currentBadge.textContent = 'Current';
//             currentBadge.style.fontSize = '0.7rem';
//             currentBadge.style.padding = '3px 6px';
//             subjectItem.querySelector('.subject-name').appendChild(currentBadge);
//         }
        
//         // Add click event
//         subjectItem.addEventListener('click', () => {
//             toggleSubjectSelection(subjectItem, subject);
//         });
        
//         subjectsGrid.appendChild(subjectItem);
//     });
    
//     // If we have existing subjects, auto-select them
//     if (existingSubjects.length > 0 && isEditMode) {
//         // Auto-select existing subjects
//         const existingSubjectIds = existingSubjects.map(s => s.id);
//         const subjectsToSelect = subjects.filter(subject => 
//             existingSubjectIds.includes(subject.id)
//         );
        
//         // Select subjects in order (first subject will be free)
//         subjectsToSelect.forEach((subject, index) => {
//             const subjectItem = document.querySelector(`.subject-item[data-subject-id="${subject.id}"]`);
//             if (subjectItem) {
//                 const existingSubject = existingSubjectsMap[subject.id];
//                 const isFirstSubject = selectedSubjects.length === 0;
                
//                 if (isFirstSubject) {
//                     // FIRST subject is FREE
//                     selectedSubjects.push({
//                         id: subject.id,
//                         name: subject.name,
//                         price: 0, // First subject free
//                         originalPrice: parseFloat(subject.subject_price),
//                         isExisting: true,
//                         wasFreeInExisting: existingSubject.free_subject_id == subject.id
//                     });
                    
//                     // Add FREE price tag
//                     const priceTag = document.createElement('span');
//                     priceTag.className = 'subject-price-tag';
//                     priceTag.textContent = 'FREE';
//                     priceTag.style.color = '#28a745';
//                     priceTag.style.fontWeight = 'bold';
//                     priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
//                     subjectItem.appendChild(priceTag);
//                 } else {
//                     // Subsequent subjects have regular price
//                     selectedSubjects.push({
//                         id: subject.id,
//                         name: subject.name,
//                         price: parseFloat(subject.subject_price),
//                         originalPrice: parseFloat(subject.subject_price),
//                         isExisting: true,
//                         wasFreeInExisting: existingSubject.free_subject_id == subject.id
//                     });
                    
//                     // Add regular price tag
//                     const priceTag = document.createElement('span');
//                     priceTag.className = 'subject-price-tag';
//                     priceTag.textContent = `PKR ${subject.subject_price}`;
//                     subjectItem.appendChild(priceTag);
//                 }
                
//                 subjectItem.classList.add('selected');
//             }
//         });
        
//         // Update breakdown if subjects are selected
//         if (selectedSubjects.length > 0) {
//             updatePriceBreakdown();
            
//             // Update checkout button text for edit mode
//             checkoutBtn.innerHTML = `
//                 <i class="fas fa-sync-alt"></i>
//                 Update Package
//             `;
            
//             // Show edit mode message
//             showSuccess(`Editing existing package with ${selectedSubjects.length} subjects. First subject is FREE.`);
//         }
//     }
// }
function updateSubjects(subjects) {
    subjectsGrid.innerHTML = '';
    selectedSubjects = [];
    
    if (!subjects || subjects.length === 0) {
        subjectsGrid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #7f8c8d;">
                <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 20px;"></i>
                <p>No subjects available for this plan</p>
            </div>
        `;
        return;
    }
    
    // Get existing subscription subjects data
    const existingSubjects = @json($activeSubscriptionSubjects ?: []);
    
    // Create a map of existing subject IDs to their data
    const existingSubjectsMap = {};
    existingSubjects.forEach(subject => {
        existingSubjectsMap[subject.id] = {
            id: subject.id,
            free_subject_id: subject.free_subject_id,
            name: subject.name,
            subject_price: subject.subject_price
        };
    });
    
    // Find which subject was originally free in the existing subscription
    let originalFreeSubjectId = null;
    existingSubjects.forEach(subject => {
        if (subject.free_subject_id == subject.id) {
            originalFreeSubjectId = subject.id;
        }
    });
    
    subjects.forEach(subject => {
        const subjectItem = document.createElement('div');
        subjectItem.className = 'subject-item';
        subjectItem.dataset.subjectId = subject.id;
        subjectItem.dataset.subjectName = subject.name;
        subjectItem.dataset.subjectPrice = subject.subject_price;
        
        const firstLetter = subject.name.charAt(0).toUpperCase();
        
        subjectItem.innerHTML = `
            <div class="subject-icon">${firstLetter}</div>
            <div class="subject-name">${subject.name}</div>
        `;
        
        // Check if this subject exists in the current subscription
        const existingSubject = existingSubjectsMap[subject.id];
        
        // Add "Current" badge if it's an existing subject
        if (existingSubject) {
            const currentBadge = document.createElement('span');
            currentBadge.className = 'badge bg-warning ms-2';
            currentBadge.textContent = 'Current';
            currentBadge.style.fontSize = '0.7rem';
            currentBadge.style.padding = '3px 6px';
            subjectItem.querySelector('.subject-name').appendChild(currentBadge);
        }
        
        // Add click event
        subjectItem.addEventListener('click', () => {
            toggleSubjectSelection(subjectItem, subject);
        });
        
        subjectsGrid.appendChild(subjectItem);
    });
    
    // If we have existing subjects, auto-select them but PRESERVE ORIGINAL FREE SUBJECT
    if (existingSubjects.length > 0 && isEditMode) {
        // Auto-select existing subjects IN THE ORIGINAL ORDER
        existingSubjects.forEach(existingSubject => {
            const subject = subjects.find(s => s.id == existingSubject.id);
            if (subject) {
                const subjectItem = document.querySelector(`.subject-item[data-subject-id="${subject.id}"]`);
                if (subjectItem) {
                    const isFreeSubject = existingSubject.free_subject_id == subject.id;
                    
                    selectedSubjects.push({
                        id: subject.id,
                        name: subject.name,
                        price: isFreeSubject ? 0 : parseFloat(subject.subject_price),
                        originalPrice: parseFloat(subject.subject_price),
                        isExisting: true,
                        wasFreeInExisting: isFreeSubject,
                        freeSubjectId: isFreeSubject ? subject.id : null
                    });
                    
                    subjectItem.classList.add('selected');
                    
                    // Add price tag
                    const priceTag = document.createElement('span');
                    priceTag.className = 'subject-price-tag';
                    
                    // if (isFreeSubject) {
                    //     priceTag.textContent = 'FREE';
                    //     priceTag.style.color = '#28a745';
                    //     priceTag.style.fontWeight = 'bold';
                    //     priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
                    // } else {
                    //     priceTag.textContent = `PKR ${subject.subject_price}`;
                    // }
                    
                    subjectItem.appendChild(priceTag);
                }
            }
        });
        
        // Update breakdown if subjects are selected
        if (selectedSubjects.length > 0) {
            updatePriceBreakdown();
            
            // Update checkout button text for edit mode
            checkoutBtn.innerHTML = `
                <i class="fas fa-sync-alt"></i>
                Update Package
            `;
            
            // Show edit mode message
            showSuccess(`Editing existing package with ${selectedSubjects.length} subjects. Original free subject preserved.`);
        }
    }
}
// Recalculate to ensure FIRST subject is always free
function recalculateFirstSubjectFree() {
    if (selectedSubjects.length === 0) return;
    
    // Make the first subject free
    selectedSubjects[0].price = 0;
    
    // Update UI for first subject
    const firstSubjectItem = document.querySelector(`.subject-item[data-subject-id="${selectedSubjects[0].id}"]`);
    if (firstSubjectItem) {
        // const priceTag = firstSubjectItem.querySelector('.subject-price-tag');
        // if (priceTag) {
        //     priceTag.textContent = 'FREE';
        //     priceTag.style.color = '#28a745';
        //     priceTag.style.fontWeight = 'bold';
        //     priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
        // }
    }
    
    // Reset other subjects to regular price
    for (let i = 1; i < selectedSubjects.length; i++) {
        selectedSubjects[i].price = selectedSubjects[i].originalPrice;
        
        const otherSubjectItem = document.querySelector(`.subject-item[data-subject-id="${selectedSubjects[i].id}"]`);
        if (otherSubjectItem) {
            const otherPriceTag = otherSubjectItem.querySelector('.subject-price-tag');
            // if (otherPriceTag) {
            //     otherPriceTag.textContent = `PKR ${selectedSubjects[i].originalPrice}`;
            //     otherPriceTag.style.color = '#0072ff';
            //     otherPriceTag.style.fontWeight = '';
            //     otherPriceTag.style.background = '';
            // }
        }
    }
}

// Toggle subject selection
// function toggleSubjectSelection(subjectItem, subject) {
//     const subjectId = subject.id;
//     const index = selectedSubjects.findIndex(s => s.id === subjectId);
    
//     if (index === -1) {
//         // Add subject
//         const isFirstSubject = selectedSubjects.length === 0;
        
//         // Check if this subject exists in the current subscription
//         const existingSubjects = @json($activeSubscriptionSubjects ?: []);
//         const existingSubject = existingSubjects.find(s => s.id === subjectId);
        
//         if (isFirstSubject) {
//             // FIRST SUBJECT IS ALWAYS FREE
//             selectedSubjects.push({
//                 id: subjectId,
//                 name: subject.name,
//                 price: 0, // First subject price is 0
//                 originalPrice: parseFloat(subject.subject_price),
//                 isExisting: existingSubject ? true : false,
//                 wasFreeInExisting: existingSubject ? (existingSubject.free_subject_id == subjectId) : false
//             });
            
//             // Update UI to show FREE
//             const priceTag = document.createElement('span');
//             priceTag.className = 'subject-price-tag';
//             priceTag.textContent = 'FREE';
//             priceTag.style.color = '#28a745';
//             priceTag.style.fontWeight = 'bold';
//             priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
//             subjectItem.appendChild(priceTag);
            
//         } else {
//             // Subsequent subjects have regular price
//             selectedSubjects.push({
//                 id: subjectId,
//                 name: subject.name,
//                 price: parseFloat(subject.subject_price),
//                 originalPrice: parseFloat(subject.subject_price),
//                 isExisting: existingSubject ? true : false,
//                 wasFreeInExisting: existingSubject ? (existingSubject.free_subject_id == subjectId) : false
//             });
            
//             // Add price tag
//             const priceTag = document.createElement('span');
//             priceTag.className = 'subject-price-tag';
//             priceTag.textContent = `PKR ${subject.subject_price}`;
//             subjectItem.appendChild(priceTag);
//         }
        
//         subjectItem.classList.add('selected');
        
//         // Add "Current" badge if it's an existing subject
//         if (existingSubject) {
//             const currentBadge = document.createElement('span');
//             currentBadge.className = 'badge bg-warning ms-2';
//             currentBadge.textContent = 'Current';
//             currentBadge.style.fontSize = '0.7rem';
//             currentBadge.style.padding = '3px 6px';
//             subjectItem.querySelector('.subject-name').appendChild(currentBadge);
//         }
        
//     } else {
//         // Remove subject
//         const removedSubject = selectedSubjects.splice(index, 1)[0];
//         subjectItem.classList.remove('selected');
        
//         // Remove price tag
//         const priceTag = subjectItem.querySelector('.subject-price-tag');
//         if (priceTag) {
//             priceTag.remove();
//         }
        
//         // Remove "Current" badge if it exists
//         const currentBadge = subjectItem.querySelector('.badge.bg-warning');
//         if (currentBadge) {
//             currentBadge.remove();
//         }
        
//         // Handle price recalculation - FIRST SUBJECT IS ALWAYS FREE
//         if (selectedSubjects.length > 0) {
//             // Make sure first subject is free
//             recalculateFirstSubjectFree();
//         }
//     }
    
//     updatePriceBreakdown();
// }
function toggleSubjectSelection(subjectItem, subject) {
    const subjectId = subject.id;
    const index = selectedSubjects.findIndex(s => s.id === subjectId);
    
    if (index === -1) {
        // Add subject
        const isFirstSubject = selectedSubjects.length === 0;
        
        // Check if this subject exists in the current subscription (for edit mode)
        const existingSubjects = @json($activeSubscriptionSubjects ?: []);
        const existingSubject = existingSubjects.find(s => s.id === subjectId);
        
        if (isFirstSubject) {
            // FIRST SUBJECT IS ALWAYS FREE
            selectedSubjects.push({
                id: subjectId,
                name: subject.name,
                price: 0,
                originalPrice: parseFloat(subject.subject_price),
                isExisting: existingSubject ? true : false,
                wasFreeInExisting: existingSubject ? (existingSubject.free_subject_id == subjectId) : false,
                freeSubjectId: subjectId
            });
            
            // Update UI to show FREE
            const priceTag = document.createElement('span');
            // priceTag.className = 'subject-price-tag';
            // priceTag.textContent = 'FREE';
            // priceTag.style.color = '#28a745';
            // priceTag.style.fontWeight = 'bold';
            // priceTag.style.background = 'rgba(40, 167, 69, 0.1)';
            subjectItem.appendChild(priceTag);
            
        } else {
            // Subsequent subjects have regular price
            selectedSubjects.push({
                id: subjectId,
                name: subject.name,
                price: parseFloat(subject.subject_price),
                originalPrice: parseFloat(subject.subject_price),
                isExisting: existingSubject ? true : false,
                wasFreeInExisting: existingSubject ? (existingSubject.free_subject_id == subjectId) : false,
                freeSubjectId: null
            });
            
            // Add price tag
            const priceTag = document.createElement('span');
            // priceTag.className = 'subject-price-tag';
            // priceTag.textContent = `PKR ${subject.subject_price}`;
            subjectItem.appendChild(priceTag);
        }
        
        subjectItem.classList.add('selected');
        
        // Add "Current" badge if it's an existing subject and not already added
        if (existingSubject && !subjectItem.querySelector('.badge.bg-warning')) {
            const currentBadge = document.createElement('span');
            currentBadge.className = 'badge bg-warning ms-2';
            currentBadge.textContent = 'Current';
            currentBadge.style.fontSize = '0.7rem';
            currentBadge.style.padding = '3px 6px';
            subjectItem.querySelector('.subject-name').appendChild(currentBadge);
        }
        
    } else {
        // Remove subject
        selectedSubjects.splice(index, 1);
        subjectItem.classList.remove('selected');
        
        // Remove price tag
        const priceTag = subjectItem.querySelector('.subject-price-tag');
        if (priceTag) {
            priceTag.remove();
        }
        
        // Remove "Current" badge if it was added dynamically
        const dynamicBadge = subjectItem.querySelector('.badge.bg-warning[style*="0.7rem"]');
        if (dynamicBadge) {
            dynamicBadge.remove();
        }
        
        // If we removed the first (free) subject, recalculate free subject
        if (index === 0 && selectedSubjects.length > 0) {
            // Make the new first subject free
            selectedSubjects[0].price = 0;
            selectedSubjects[0].freeSubjectId = selectedSubjects[0].id;
            
            // Update UI for new free subject
            const newFirstSubjectItem = document.querySelector(`.subject-item[data-subject-id="${selectedSubjects[0].id}"]`);
            if (newFirstSubjectItem) {
                const newPriceTag = newFirstSubjectItem.querySelector('.subject-price-tag');
                if (newPriceTag) {
                    // newPriceTag.textContent = 'FREE';
                    newPriceTag.style.color = '#28a745';
                    newPriceTag.style.fontWeight = 'bold';
                    newPriceTag.style.background = 'rgba(40, 167, 69, 0.1)';
                }
            }
            
            // Reset other subjects to regular price
            for (let i = 1; i < selectedSubjects.length; i++) {
                selectedSubjects[i].price = selectedSubjects[i].originalPrice;
                selectedSubjects[i].freeSubjectId = null;
                
                const otherSubjectItem = document.querySelector(`.subject-item[data-subject-id="${selectedSubjects[i].id}"]`);
                if (otherSubjectItem) {
                    const otherPriceTag = otherSubjectItem.querySelector('.subject-price-tag');
                    if (otherPriceTag) {
                        // otherPriceTag.textContent = `PKR ${selectedSubjects[i].originalPrice}`;
                        otherPriceTag.style.color = '#0072ff';
                        otherPriceTag.style.fontWeight = '';
                        otherPriceTag.style.background = '';
                    }
                }
            }
        }
    }
    
    updatePriceBreakdown();
}

// Update price breakdown
function updatePriceBreakdown() {
    breakdownList.innerHTML = '';
    
    if (selectedSubjects.length === 0) {
        breakdownList.innerHTML = '<div style="text-align: center; color: #7f8c8d; padding: 20px;">Select subjects to see breakdown</div>';
        if (grandTotalSection) grandTotalSection.style.display = 'none';
        if (grandTotalDisplay) grandTotalDisplay.style.display = 'none';
        return;
    }
    
    // Calculate plan total
    const planTotal = basePrice * duration;
    
    // Calculate subjects total
    let subjectsTotal = 0;
    let freeSubjectCount = 0;
    let freeSubjectName = '';
    
    selectedSubjects.forEach((subject, index) => {
        const subjectTotal = subject.price * duration;
        subjectsTotal += subjectTotal;
        
        if (subject.price === 0) {
            freeSubjectCount++;
            if (!freeSubjectName) freeSubjectName = subject.name;
        }
        
        // Add subject to breakdown
        const itemDiv = document.createElement('div');
        itemDiv.className = 'breakdown-item';
        
        if (subject.price === 0) {
            // Free subject
            // let freeLabel = 'First Subject Free';
            // if (subject.wasFreeInExisting && subject.isExisting) {
            //     freeLabel = 'Previously Free';
            // }
            
            itemDiv.innerHTML = `
                <div>
                    <span class="item-name">${subject.name} <span style="color: #28a745; font-size: 0.9rem;">(${freeLabel})</span></span>
                </div>
               
            `;
        } else if (subject.isExisting) {
            // Existing subject in edit mode
            itemDiv.innerHTML = `
                <div>
                    <span class="item-name">${subject.name} <span style="color: #ffc107; font-size: 0.9rem;">(Current)</span></span>
                </div>
                <span class="item-price">PKR ${parseInt(subjectTotal).toLocaleString()}</span>
            `;
        } else {
            // Regular paid subject
            itemDiv.innerHTML = `
                <span class="item-name">${subject.name}</span>
                <span class="item-price">PKR ${parseInt(subjectTotal).toLocaleString()}</span>
            `;
        }
        
        breakdownList.appendChild(itemDiv);
    });
    
    // Calculate grand total
    const grandTotal = planTotal + subjectsTotal;
    
    // Update breakdown totals
    if (planTotalAmount) planTotalAmount.textContent = `PKR ${parseInt(planTotal).toLocaleString()}`;
    if (totalPrice) totalPrice.textContent = `PKR ${parseInt(planTotal).toLocaleString()}`;
    if (subjectsTotalAmount) subjectsTotalAmount.textContent = `PKR ${parseInt(subjectsTotal).toLocaleString()}`;
    if (grandTotalAmount) grandTotalAmount.textContent = `PKR ${parseInt(grandTotal).toLocaleString()}`;
    
    // Update grand total display
    if (grandTotalDisplayAmount) grandTotalDisplayAmount.textContent = `PKR ${parseInt(grandTotal).toLocaleString()}`;
    
    // Show totals
    if (grandTotalSection) grandTotalSection.style.display = 'block';
    if (grandTotalDisplay) grandTotalDisplay.style.display = 'block';
    
    // Show free subject notice
    if (freeSubjectCount > 0) {
        const noticeDiv = document.createElement('div');
        noticeDiv.className = 'alert alert-success mt-3';
        noticeDiv.style.padding = '10px';
        noticeDiv.style.fontSize = '0.9rem';
        
        let noticeText = '<i class="fas fa-gift me-2"></i>';
        if (isEditMode && selectedSubjects.some(s => s.isExisting && s.wasFreeInExisting)) {
            noticeText = '<i class="fas fa-history me-2"></i><strong>Note:</strong>The first subject amount is already included in the package price. The remaining subject will be charged as per the selection.';
        }
        
        noticeDiv.innerHTML = noticeText;
        breakdownList.appendChild(noticeDiv);
    }
}

// Update price breakdown
function updatePriceBreakdown() {
    breakdownList.innerHTML = '';
    
    if (selectedSubjects.length === 0) {
        breakdownList.innerHTML = '<div style="text-align: center; color: #7f8c8d; padding: 20px;">Select subjects to see breakdown</div>';
        grandTotalSection.style.display = 'none';
        grandTotalDisplay.style.display = 'none';
        return;
    }
    
    // Calculate plan total
    const planTotal = basePrice * duration;
   
    // Calculate subjects total (FIRST subject is free)
    let subjectsTotal = 0;
    let freeSubjectCount = 0;
    
    selectedSubjects.forEach((subject, index) => {
        const subjectTotal = subject.price * duration;
        subjectsTotal += subjectTotal;
        
        if (subject.price === 0) freeSubjectCount++;
        
        // Add subject to breakdown
        const itemDiv = document.createElement('div');
        itemDiv.className = 'breakdown-item';
        
        if (index === 0 && subject.price === 0) {
            // FIRST subject is FREE (always)
            itemDiv.innerHTML = `
                <div>
                    <span class="item-name">${subject.name}</span>
                </div>
            `;
        } else if (subject.isExisting) {
            // Existing subject in edit mode
            if(subjectTotal > 0){
            itemDiv.innerHTML = `
                <div>
                    <span class="item-name">${subject.name} <span style="color: #ffc107; font-size: 0.9rem;">(Current)</span></span>
                </div>
                
                    <span class="item-price">PKR ${parseInt(subjectTotal).toLocaleString()}</span>`;
                }else{
                  itemDiv.innerHTML = `
                  <div>
                    <span class="item-name">${subject.name} <span style="color: #ffc107; font-size: 0.9rem;">(Current)</span></span>
                </div>`;
                }
        } else {
            // Regular paid subject
            itemDiv.innerHTML = `
                <span class="item-name">${subject.name}</span>
                <span class="item-price">PKR ${parseInt(subjectTotal).toLocaleString()}</span>
            `;
        }
        
        breakdownList.appendChild(itemDiv);
    });
    
    // Calculate grand total
    const grandTotal = planTotal + subjectsTotal;
    
    // Update breakdown totals
    planTotalAmount.textContent = `PKR ${parseInt(planTotal).toLocaleString()}`;
    totalPrice.textContent = `PKR ${parseInt(planTotal).toLocaleString()}`;
    subjectsTotalAmount.textContent = `PKR ${parseInt(subjectsTotal).toLocaleString()}`;
    grandTotalAmount.textContent = `PKR ${parseInt(grandTotal).toLocaleString()}`;
    
    // Update grand total display
    grandTotalDisplayAmount.textContent = `PKR ${parseInt(grandTotal).toLocaleString()}`;
    
    // Show totals
    grandTotalSection.style.display = 'block';
    grandTotalDisplay.style.display = 'block';
    
    // Show free subject notice
    if (freeSubjectCount > 0) {
        const noticeDiv = document.createElement('div');
        noticeDiv.className = 'alert alert-success mt-3';
        noticeDiv.style.padding = '10px';
        noticeDiv.style.fontSize = '0.9rem';
        
        let noticeText = '';
        
        noticeDiv.innerHTML = noticeText;
        breakdownList.appendChild(noticeDiv);
    }
}

// Duration controls
decreaseBtn.addEventListener('click', () => {
    if (duration > 1) {
        duration--;
        durationValue.textContent = duration;
        updateDurationPlural();
        updatePlanPriceDisplay();
        updatePriceBreakdown();
    }
});

increaseBtn.addEventListener('click', () => {
    if (duration < maxDuration) {
        duration++;
        durationValue.textContent = duration;
        updateDurationPlural();
        updatePlanPriceDisplay();
        updatePriceBreakdown();
    }
});

// Update duration plural text
function updateDurationPlural() {
    durationPlural.textContent = duration > 1 ? 's' : '';
}

// Checkout handler
checkoutBtn.addEventListener('click', async () => {
    if (!currentPlanId) {
        Swal.fire({
            icon: 'warning',
            title: 'Plan Selection Required',
            text: 'Please select a plan first.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0072ff',
            background: '#ffffff',
            color: '#2c3e50',
            width: 400
        });
        return;
    }
    
    if (selectedSubjects.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Subjects Required',
            text: 'Please select at least one subject.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0072ff',
            background: '#ffffff',
            color: '#2c3e50',
            width: 400
        });
        return;
    }
    
    // Calculate totals
    const planTotal = basePrice * duration;
    let subjectsTotal = 0;
    let freeSubjectCount = 0;
    let freeSubjectName = '';
    
    selectedSubjects.forEach((subject, index) => {
        if (subject.price === 0) {
            freeSubjectCount++;
            if (!freeSubjectName) freeSubjectName = subject.name;
        } else {
            subjectsTotal += subject.price * duration;
        }
    });
    
    const grandTotal = planTotal + subjectsTotal;
    
    // Prepare confirmation message
    const title = isEditMode ? 'Update Package Subscription' : 'Confirm Subscription Purchase';
    const confirmButtonText = isEditMode ? 'Update Package' : 'Confirm & Pay';
    const icon = isEditMode ? 'info' : 'question';
    
    // Create confirmation HTML
    const confirmationHTML = `
        <div class="text-start">
            <div class="confirmation-summary mb-4">
                <h6 class="text-primary mb-3"><i class="fas fa-file-invoice me-2"></i>Order Summary</h6>
                
                <div class="row mb-2">
                    <div class="col-6">
                        <span class="text-muted">Plan:</span>
                    </div>
                    <div class="col-6 text-end">
                        <strong>${planName.textContent}</strong>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-6">
                        <span class="text-muted">Duration:</span>
                    </div>
                    <div class="col-6 text-end">
                        <strong>${duration} month${duration > 1 ? 's' : ''}</strong>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <span class="text-muted">Subjects (${selectedSubjects.length}):</span>
                    </div>
                    <div class="col-6 text-end">
                        <div class="subject-list">
                            ${selectedSubjects.map((subject, index) => `
                                <div class="small">
                                    ${subject.name} 
                                    ${subject.price === 0 ? '' : ''}
                                    ${subject.isExisting ? '<span class="badge bg-warning ms-1">CURRENT</span>' : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <!-- Price Breakdown -->
                <div class="price-breakdown">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Plan Price (${duration} month${duration > 1 ? 's' : ''}):</span>
                        <span class="fw-semibold">PKR ${parseInt(planTotal).toLocaleString()}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subjects Price:</span>
                        <span class="fw-semibold">
                            ${freeSubjectCount > 0 ? 
                                `PKR ${parseInt(subjectsTotal).toLocaleString()} (${selectedSubjects.length - freeSubjectCount} paid)` :
                                `PKR ${parseInt(subjectsTotal).toLocaleString()}`
                            }
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-bold">Total Amount:</span>
                        <span class="h5 text-primary fw-bold">PKR ${parseInt(grandTotal).toLocaleString()}</span>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-light border mb-4">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-primary mt-1 me-2"></i>
                    <div class="small">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Show SweetAlert confirmation
    const result = await Swal.fire({
        title: title,
        html: confirmationHTML,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: `<i class="fas fa-${isEditMode ? 'sync-alt' : 'credit-card'} me-2"></i>${confirmButtonText}`,
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancel',
        confirmButtonColor: isEditMode ? '#ffc107' : '#0072ff',
        cancelButtonColor: '#6c757d',
        background: '#ffffff',
        color: '#2c3e50',
        width: 550,
        customClass: {
            popup: 'swal2-checkout-popup',
            confirmButton: 'swal2-checkout-confirm',
            cancelButton: 'swal2-checkout-cancel',
            title: 'swal2-checkout-title'
        },
        showLoaderOnConfirm: true,
        preConfirm: async () => {
            try {
                // Process checkout
                const checkoutResult = await processCheckout(grandTotal);
                
                if (!checkoutResult.success) {
                    throw new Error(checkoutResult.message || 'Failed to process request');
                }
                
                return checkoutResult;
            } catch (error) {
                Swal.showValidationMessage(
                    `<div class="text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${error.message || 'Please try again'}
                    </div>`
                );
                return false;
            }
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
    
    // Check if user clicked confirm button
    if (result.isConfirmed && result.value) {
        const checkoutData = result.value;
        
        // Update current subscription ID
        currentSubscriptionId = checkoutData.subscription_id;
        
        // Show success message
        await Swal.fire({
            title: isEditMode ? 'Package Updated!' : 'Subscription Created!',
            html: `
                <div class="text-center">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 60px;"></i>
                    </div>
                    <h5 class="mb-2">${isEditMode ? 'Package Updated' : 'Subscription'} #${checkoutData.subscription_id}</h5>
                    <p class="mb-3">${isEditMode ? 'Your package has been updated successfully.' : 'Your subscription has been created successfully.'}</p>
                    ${freeSubjectCount > 0 ? `
                        <div class="alert alert-success small">
                            <i class="fas fa-gift me-2"></i>
                             ${freeSubjectName}
                        </div>
                    ` : ''}
                    <div class="alert alert-info small mt-2">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Redirecting to payment section...
                    </div>
                </div>
            `,
            icon: 'success',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#ffffff',
            color: '#2c3e50',
            width: 450,
            willClose: () => {
                // This runs when the success modal closes
                // Show payment section
                paymentSection.style.display = 'block';
                
                // Update payment section with subscription details
                updatePaymentSection(checkoutData.subscription_id, grandTotal);
                
                // Scroll to payment section smoothly
                setTimeout(() => {
                    paymentSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }
        });
    }
});

// Process checkout function
async function processCheckout(totalAmount) {
    try {
        // Calculate plan and subjects totals
        const planTotal = basePrice * duration;
        let subjectsTotal = 0;
        let subjectDetails = [];
        
        selectedSubjects.forEach((subject, index) => {
            const subjectPrice = subject.price * duration;
            subjectsTotal += subjectPrice;
            
            subjectDetails.push({
                subject_id: subject.id,
                original_price: subject.originalPrice,
                charged_price: subjectPrice,
                is_free: subject.price === 0,
                is_existing: subject.isExisting || false,
                was_free_in_existing: subject.wasFreeInExisting || false
            });
        });
        
        const response = await fetch('/student/payment-method/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                plan_id: currentPlanId,
                subject_ids: selectedSubjects.map(s => s.id),
                duration: duration,
                total_amount: totalAmount,
                plan_price: planTotal,
                subjects_price: subjectsTotal,
                subject_details: subjectDetails,
                existing_subscription_id: isEditMode ? "{{ $existingSubscription->id ?? '' }}" : null
            })
        });
        
        const data = await response.json();
        console.log(data)
        // Return the data for SweetAlert to handle
        return data;
        
    } catch (error) {
        console.error('Error in processCheckout:', error);
        return {
            success: false,
            message: 'Network error. Please try again.'
        };
    }
}

// Utility functions
function showLoading() {
    loadingOverlay.classList.add('active');
}

function hideLoading() {
    loadingOverlay.classList.remove('active');
}

function showError(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    successMessage.style.display = 'none';
}

function showSuccess(message) {
    successMessage.textContent = message;
    successMessage.style.display = 'block';
    errorMessage.style.display = 'none';
}

// Update payment section with subscription details
function updatePaymentSection(subscriptionId, totalAmount) {
    // Update payment forms with subscription ID and amount
    updatePaymentForms(subscriptionId, totalAmount);
}

// Update payment forms with subscription details
function updatePaymentForms(subscriptionId, totalAmount) {
    // Set values in Handshake form
    if (document.getElementById('HS_TransactionReferenceNumber')) {
        document.getElementById('HS_TransactionReferenceNumber').value = subscriptionId;
    }
    
    if (document.getElementById('HS_ReturnURL')) {
        document.getElementById('HS_ReturnURL').value = "{{ route('payment.response', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', subscriptionId);
    }
    
    // Set values in PageRedirection form
    if (document.getElementById('TransactionReferenceNumber')) {
        document.getElementById('TransactionReferenceNumber').value = subscriptionId;
    }
    
    if (document.getElementById('TransactionAmount')) {
        document.getElementById('TransactionAmount').value = totalAmount;
    }
    
    if (document.getElementById('ReturnURL')) {
        document.getElementById('ReturnURL').value = "{{ route('payment.response', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', subscriptionId);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // If there's an existing subscription, pre-select the plan
    @if($existingSubscription && $existingSubscription->plan_id)
        setTimeout(() => {
            planSelect.value = {{ $existingSubscription->plan_id }};
            loadPlanDetails({{ $existingSubscription->plan_id }});
            
            @if(isset($activeSubscriptionDetails))
                duration = {{ $activeSubscriptionDetails->duration_months }};
                durationValue.textContent = duration;
                updateDurationPlural();
                
                const planTotal = basePrice * duration;
                totalPrice.textContent = `PKR ${parseInt(planTotal).toLocaleString()}`;
            @endif
        }, 500);
    @endif
});
</script>
@endpush
