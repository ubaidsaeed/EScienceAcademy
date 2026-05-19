
@php
    $setting = \App\Models\Setting::first(); // ✅ Fetch only one record
    $address = $setting->address ?? '';
    $mail = $setting->contact_mail ?? '';
@endphp

<style>
    .message {
        top: 0;
        position: fixed;
        float: right;
        left: 70%;
        z-index: 999;
        color: #664d03
    }

    @media (max-width: 500px) {
        .message {
            top: 0;
            position: fixed;
            font-size: 12px !important;
            float: right;
            left: 0% !important;
            z-index: 999;
            width: max-content;
            color: #664d03
        }
    }

    @media (max-width: 1024px) {
        .message {
            top: 0;
            position: fixed;
            float: right;
            left: 60%;
            z-index: 999;
            color: #664d03
        }
    }
</style>
<section class="cs__contact__area">
    <div class="container">
        <div class="section-inner mt--150">
            <div class="contact-form bg-white">
                <div class="section__title__area text-center mb--50">
                    <p class="section__title__area__sub-title m-auto mb--15 color-one wow fadeInUp" data-wow-delay=".25s">
                        Career Form</p>
                    <h2 class="section__title__area__title text-anim">Fill It Out</h2>
                </div>
                <form action="{{ route('careers.form') }}" method="post" enctype="multipart/form-data"
                    class="p-3 rounded">
                    @csrf
                    <div class="single-input-wrapper">
                        <label for="name" class="name" id="Name">Enter Your Name</label>
                        <input type="text" name="name" placeholder="Enter Your Name" required="true"
                            value="{{ old('name') }}">
                        @error('name')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input-wrapper">
                        <label for="email" class="email" id="email">Enter Your Email</label>
                        <input type="email" name="email" placeholder="Enter Your Email" required="true"
                            value="{{ old('email') }}">
                        @error('email')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input-wrapper">
                        <label for="position" class="position" id="position">Label Your Preferred position</label>
                        <input type="text" name="position" placeholder="Required position" required="true"
                            value="{{ old('position') }}">
                        @error('position')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input-wrapper">
                        <label for="contact_no" class="contact-label" id="contact_no">Enter Your Contact</label>
                        <input type="number" name="contact_no" placeholder="Enter Your Contact Number" required="true"
                            value="{{ old('contact_no') }}">
                        @error('contact_no')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input-wrapper">
                        <label for="file-upload" class="file-label" id="file-label">Upload Your Resume</label>
                        <input type="file" name="resume" id="file-upload" class="file-input" required
                            value="{{ old('resume') }}" accept="application/pdf">
                        @error('resume')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input-wrapper">
                            <label for="cover_letter" class="cover_letter" id="cover_letter">Write Your Cover Letter</label>
                        <textarea name="cover_letter" id="contact" placeholder="Cover Letter">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                     <div class="single-input-wrapper">
                   <label for="contact" class="mt--20 mb--20"><span class="color-one">*</span>
                   <input type="checkbox" name="policy" class="policy-checkbox"> By clicking 'Submit', you confirm that the information provided is accurate and complete to the best of your knowledge. You agree to be bound by our <a href="https://escienceacademy.com/terms-conditions"><b>Terms and Conditions</b></a> and acknowledge that you have read our <a href="https://escienceacademy.com/privacy-policy"><b>Privacy Policy</b></a></label>
                    @error('policy')
                        <span class="error" style="color: red">{{ $message }}</span>
                    @enderror  
                    </div>
                    <button type="submit" class="form-btn mt--40">
                        <span class="rounded-button bg-one">
                            <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 17L17 1H7.8" stroke="#ffffff"></path>
                            </svg>
                        </span>
                        <span class="cs-btn bg-one">Submit</span>
                    </button>
                </form>
            </div>
            </div>
    </div>
</section>

<script>
    setTimeout(function() {
        $('.message').fadeOut('fast');
    }, 5000);
</script>
@if (session('success'))
    <div class="alert alert-successs message">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong></strong> {{ session('success') }}.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-errors message">

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong></strong> {{ session('error') }}.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
