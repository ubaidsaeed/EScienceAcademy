<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --accent: #4895ef;
        --light: #f8f9fa;
        --dark: #212529;
        --success: #4cc9f0;
        --warning: #f72585;
        --light-gray: #e9ecef;
        --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(20px);
        transition: transform 0.3s ease;
        position: relative;
    }

    .modal.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark);
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray);
        transition: color 0.2s;
    }

    .close-btn:hover {
        color: var(--dark);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--light-gray);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn {
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--secondary);
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--light-gray);
        color: var(--gray);
    }

    .btn-outline:hover {
        background-color: var(--light-gray);
    }

    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: 1fr;
        }

        .header h1 {
            font-size: 2rem;
        }

        .modal-content {
            width: 95%;
        }
    }

    .header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .header h1 {
        font-size: 2.5rem;
        color: var(--secondary);
        margin-bottom: 1rem;
        background: linear-gradient(90deg, var(--primary), var(--warning));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .header p {
        font-size: 1.1rem;
        color: #666;
        max-width: 700px;
        margin: 0 auto;
    }

    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        position: relative;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .card-number {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .card-image {
        height: 180px;
        background-size: cover;
        background-position: center;
    }

    .card-1 .card-image {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .card-2 .card-image {
        background: linear-gradient(135deg, #f72585, #b5179e);
    }

    .card-3 .card-image {
        background: linear-gradient(135deg, #4cc9f0, #4895ef);
    }

    .card-4 .card-image {
        background: linear-gradient(135deg, #3a5a40, #588157);
    }

    .card-content {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.4rem;
        margin-bottom: 0.8rem;
        color: var(--dark);
    }

    .card-description {
        color: #666;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        text-align: justify;
    }

    .card-button {
        display: inline-block;
        padding: 0.7rem 1.5rem;
        background: var(--primary);
        color: white;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 500;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .card-button:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .card-1 .card-button {
        background: var(--primary);
    }

    .card-2 .card-button {
        background: #f72585;
    }

    .card-3 .card-button {
        background: #4cc9f0;
    }

    .card-4 .card-button {
        background: #3a5a40;
    }

    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: 1fr;
        }

        .header h1 {
            font-size: 2rem;
        }
    }

    /* Modern badge for card category */
    .card-category {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-7 .card-category {
        color: var(--primary);
    }

    .card-5 .card-category {
        color: #f72585;
    }

    .card-6 .card-category {
        color: #4cc9f0;
    }

    .card-8 .card-category {
        color: #3a5a40;
    }

    /* .button{
            padding: 1.5rem;
            display: flex;
        } */
        
         /* Celebration Popup Styles */
    .celebration-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s ease;
    }

    .celebration-popup.active {
        opacity: 1;
        z-index: 9999 !important;
        pointer-events: all;
    }

    .celebration-content {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: scale(0.8);
        transition: transform 0.5s ease;
    }

    .celebration-popup.active .celebration-content {
        transform: scale(1);
    }

    .celebration-icon .fa-trophy {
        font-size: 5rem;
        color: var(--gold);
        margin-bottom: 1.5rem;
        animation: bounce 1s infinite alternate;
    }

    .celebration-icon .fa-times {
        font-size: 5rem;
        color: var(--danger-color);
        margin-bottom: 1.5rem;
        animation: bounce 1s infinite alternate;
    }

    .celebration-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark);
        background: linear-gradient(90deg, var(--primary), var(--accent));
        -webkit-background-clip: text;
        background-clip: text;
        /* color: transparent; */
    }

    .celebration-message {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* .close-btn {
        padding: 0.8rem 1.8rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        background-color: #002147;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    } */

    /* .close-btn:hover {
        background: #0e37c5;
        color: white
    } */

    /* Confetti Styles */
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #f00;
        opacity: 0;
    }

    /* Animations */
    @keyframes bounce {
        0% {
            transform: translateY(0) scale(1);
        }

        100% {
            transform: translateY(-20px) scale(1.1);
        }
    }

    @keyframes confetti-fall {
        0% {
            transform: translateY(-100px) rotate(0deg);
            opacity: 1;
        }

        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    /* Responsive */
    @media (max-width: 600px) {
        .celebration-content {
            padding: 2rem 1.5rem;
        }

        .celebration-title {
            font-size: 1.5rem;
        }

        .celebration-icon {
            font-size: 4rem;
        }

    }
</style>
@php
    use Illuminate\Support\Facades\DB;

    $quizTabRecord = DB::table('quizs')->where('status', 'active')->get();

    function firstSentence($text)
    {
        return explode(' ', trim($text))[0] ?? '';
    }
     
    $cardStyles = [
        ['gradient' => 'linear-gradient(135deg, #4361ee, #3a0ca3)', 'button' => 'var(--primary)'],
    
    ];
@endphp
{{-- <div class="header">
    <h1>CBT & Teaching Techniques</h1>
    <p>Explore our comprehensive resources on Cognitive Behavioral Therapy and effective teaching methodologies</p>
</div> --}}

<div class="cards-container">
    @foreach ($quizTabRecord as $index => $item)
        @php
            $styleIndex = $index % count($cardStyles); // Cycle through colors
        @endphp
        <div class="card">
            <div class="card-number">{{ $index + 1 }}</div>
            <!--<div class="card-category">{{ $item->name }}</div>-->
            <div class="card-image" style="@if($item->image) background-image:url({{asset('images/quiz/image/'.$item->image)}}); @else background: {{ $cardStyles[$styleIndex]['gradient'] }}; @endif"></div>
            <div class="card-content">
                <p class="card-title"><b>{{ $item->name }}</b></p>
                <p class="card-description">{!! $item->content !!}</p>
                <!--<button class="card-button" style="background: {{ $cardStyles[$styleIndex]['button'] }};margin-top: 100%;"-->
                <!--    onclick='openModal(@json(["name" => $item->name, "id" => $item->id]))'>-->
                <!--    Take Quiz-->
                <!--</button>-->
            </div>
            <div style="display: flex;
    justify-content: center;
    margin-top: auto;
    margin-bottom: 20px;">
                 <button class="card-button" style="background: {{ $cardStyles[$styleIndex]['button'] }};"
                    onclick='openModal(@json(["name" => $item->name, "id" => $item->id]))'> <i class="fas fa-play-circle"></i>
                    Take Quiz
                </button>
            </div>
            
        </div>
    @endforeach
     {{-- popup --}}
    <div class="celebration-popup" id="celebrationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button class="close-btn" onclick="closePopupModal()">&times;</button>
            </div>
            <div class="celebration-content">
                <div class="celebration-icon">
                    <i class="tropy fa" id="tropy"></i>
                </div>
                <h2 class="celebration-title"></h2>
                <p class="celebration-message">
                </p>
                <button class="close-btn" id="closeBtn" style="display: none"></button>
            </div>
        </div>
    </div>
</div>
<!-- Modal 7 - CBT Overview -->
<div id="modal" class="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">CBT Overview Registration</h3>
            <button class="close-btn" onclick="closeModal('modal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="quiz-submition">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                {{ csrf_field() }}
                <div class="row">
            <input type="hidden" id="id" name="id" value="">
            <div class="form-group col-6">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    placeholder="Enter your name" required>
            </div>
            <div class="form-group col-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="Enter your email" required>
                <span id="emailError" style="color: red;"></span>
            </div>
            <div class="form-group col-6">
                <label for="contact_no" class="form-label">Contact No</label>
                <input type="text" id="contact_no" name="contact_no" required class="form-control"
                    placeholder="Enter your contact no">
            </div>
            <div class="form-group col-6">
                <label for="city" class="form-label">City</label>
                <input type="text" id="city" name="city" class="form-control"
                    placeholder="Enter your city" required>
            </div>
            <div class="form-group col-12">
                <label for="experience" class="form-label">TVET Experience</label>
                <input type="text" id="experience" name="experience" class="form-control"
                    placeholder="Enter your TVET experience">
            </div>
        </div>

                <div class="modal-footer">
                    <button class="btn btn-outline" onclick="closeModal('modal')">Cancel</button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
<!-- jQuery -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Inputmask (use bundle, not core only) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/jquery.inputmask.bundle.min.js"></script>

<script>
// Initialize Inputmask after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
              // Phone number mask (e.g., 03XX-XXXXXXX)
            Inputmask({
                mask: "0399-9999999",
                placeholder: "03__-_______",
                showMaskOnHover: true,
                showMaskOnFocus: true
            }).mask(document.getElementById('contact_no'));

            // Email mask
            Inputmask({
                alias: "email",
                placeholder: "example@domain.com"
            }).mask(document.getElementById('email'));

            // Name mask (letters and spaces only)
            Inputmask({
                regex: "[A-Za-z\\s]*",
                placeholder: "John Doe"
            }).mask(document.getElementById('name'));

            // City mask (letters, spaces, and hyphens only)
            Inputmask({
                regex: "[A-Za-z\\s\\-]*",
                placeholder: "New York"
            }).mask(document.getElementById('city'));

            // Experience mask (numbers only, max 2 digits)
            Inputmask({
                mask: "99",
                placeholder: "00",
                definitions: {
                    '9': {
                        validator: "[0-9]",
                        cardinality: 1
                    }
                }
            }).mask(document.getElementById('experience'));
        });
   
        function openModal(data) {

            document.querySelector('#id').value = data.id;
            document.getElementById('modal').classList.add('active');
            document.body.style.overflow = 'hidden';
            document.querySelector('.modal-title').innerHTML = data.name;
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
             const myForm = document.getElementById('quiz-submition');
             const inputFields = form.querySelectorAll('input');
             inputFields.forEach(input => {
               input.value = ''; // Clear value input field
             });
        }
         function closePopupModal() {
            const celebrationPopup = document.getElementById('celebrationPopup');
            celebrationPopup.classList.remove('active');
        }
        // Close modal when clicking outside of it
        // window.addEventListener('click', function(event) {
        //     if (event.target.classList.contains('modal')) {
        //         event.target.classList.remove('active');
        //         document.body.style.overflow = 'auto';
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {
            const myForm = document.getElementById('quiz-submition');

           if (!myForm) return;
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');

            function validateEmail(email) {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return emailRegex.test(email);
            }
            myForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const emailValue = emailInput.value;
                if (validateEmail(emailValue)) {
                    emailError.textContent = '';
                    const formData = new FormData(event.target);
                    const formObject = Object.fromEntries(formData.entries());

                    const payload = {
                        formObject: formObject,
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content"),
                    };

                    const data = JSON.stringify(payload);
                    const url = "/quiz/submit-form";

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", url, true);
                    xhr.setRequestHeader("Content-Type", "application/json");

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const response = JSON.parse(xhr.responseText);
                                // Now you can safely use:
                                const quizId = response.quiz_id;
                                const userId = response.user_id;

                                // window.location.href = `/quiz/questions/${quizId}/${userId}`;
                                  if (response.message == 'Printed') {
                                    const celebrationPopup = document.getElementById(
                                        'celebrationPopup');
                                    const title = celebrationPopup.querySelector(".celebration-title");
                                    const message = celebrationPopup.querySelector(
                                        ".celebration-message");
                                    const button = celebrationPopup.querySelector("#closeBtn");

                                    // Update content for Printed case
                                    title.innerText = "Certificate Already Printed";
                                    message.innerHTML =
                                        `Your certificate has already been printed.<br>
                                         You can attempt again by clicking
                                         <a href="/quiz/questions/${quizId}/${userId}" style="color:#007bff; text-decoration:underline;">here</a>.`;

                                    button.style.display = "none";
                                    celebrationPopup.classList.add("active");
                                    // Certificate is already printed
                                } else {
                                    const celebrationPopup = document.getElementById(
                                        'celebrationPopup');
                                    celebrationPopup.classList.remove('active');
                                    window.location.href = `/quiz/questions/${quizId}/${userId}`;
                                }
                            } else {
                                console.error("Request failed:", xhr.statusText);
                                alert("Something went wrong! Please try again.");
                            }
                        }
                    };

                    xhr.send(data);
                } else {
                    emailError.textContent = 'Please enter a valid email address.';
                }
            });
        });
        
     
    </script>
@endsection
