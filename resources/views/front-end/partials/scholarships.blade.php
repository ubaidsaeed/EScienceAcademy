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

<section class="cs-project-details-area">
    <div class="container">
        <div class="project-details-inner">
            <div class="image-area">
                <img src="{{ asset('build/assets/frontend/images/project/Generated_Image_r2646r2646r2646r.png') }}" alt="">
            </div>
            <div class="content-area">
                <div class="row">
                    <div class="col-lg-7">
                        <h2 class="section-title text-capitalize mt--40">We are committed to supporting talented and
                            deserving students</h2>

                        <p class="mb--30">We are committed to supporting talented and deserving students through various
                            scholarship programs. To apply, please ensure you have the following documents ready for
                            submission.</p>
                        <h3 class="title">Required Documents</h3>
                        <p class="mb--30">All applicants must provide the following documents to be considered for
                            scholarship eligibility:</p>
                        <h3 class="title">1. Parent's National ID Cards</h3>
                        <p class="mb--30">Scanned copies of both parent's valid identification cards.</p>
                        <h3 class="title">2. Proof of Permanent Residence</h3>
                        <p class="mb--30">Acceptable documents include a residence certificate or a domicile letter from
                            a competent authority.</p>
                        <h3 class="title">3. Recent Electricity Bills</h3>
                        <p class="mb--30">Submit the most recent electricity bill (last 3 months) as proof of residence
                            and financial background.</p>
                        <h3 class="title">4. Academic Records</h3>
                        <p class="mb--30">Copies of the most recent academic transcripts and certificates from
                            recognized institutions.</p>
                        <h3 class="title">5. Achievements & Awards</h3>
                        <p class="mb--30">Documentation of any academic, extracurricular, or community service
                            achievements.</p>
                        <h3 class="title">6. Parental Bank Maintenance Certificate</h3>
                        <p class="mb--30">A certificate from the bank outlining average balance and transaction activity
                            for the past 6 months.</p>
                            <h3 class="title">7. Recent Parent's Salary Slip</h3>
                        <p class="mb--30">A certificate from the bank outlining average balance and transaction activity
                            for the past 6 months.</p>
                        <h3 class="title">Important Notes:</h3>
                        <p class="mb--30">* All documents must be clear, legible, and in PDF or JPEG format.
                            <br>* Incomplete or falsified submissions will lead to immediate disqualification. <br>
                            * Additional documents may be requested during the review process.
                        </p>
                        <p class="mb--30">
                            To ensure transparency and fairness, eScience Academy enforces a zero-tolerance policy on the submission of false,
                            forged, or misleading information for scholarships. This includes:
                            </p>
                            <p class="mb--30">
                                * Altered or forged identification cards, bank statements, academic records, or utility bills
                                <br>* Use of another individual's information without consent<br>
                                * Misrepresentation of income, residency, or academic performance
                                </p>
                                <h3 class="title">Consequences of Violation:</h3>
                                 <p class="mb--30">
                                * Immediate disqualification from the scholarship program
                                <br>* Permanent ban from future applications<br>
                                * Termination of account access if enrolled under false information<br>
                                * Potential legal action if necessary under applicable laws<br>
                                * We value honesty and integrity in all scholarship applications and encourage applicants to represent  
                                  their circumstances truthfully
                                </p>
                        <h3 class="title">Submission Guidelines:</h3>
                        <p class="mb--30">* Ensure all files are named correctly (e.g., `Parent_ID_JohnDoe.pdf`,
                            `Transcript_Grade12.jpg`).
                            <br>* Applications must be submitted before the deadline stated on the portal.
                        </p>

                    </div>
                    <div class="col-lg-5">
                        <div class="project-right-sidebar">
                             <form action="{{ route('scholarships.form') }}" method="post" enctype="multipart/form-data"
                                class="p-3 border rounded bg-light">
                                @csrf
                                <div class="mb-4 font-sm ">
                                    <label for="name" class="form-label"><b>Full Name:</b></label>
                                    <input type="text" class="form-control border" id="name" name="name"
                                        placeholder="Enter your name" required value="{{ old('name') }}">
                                    @error('name')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!--new fields-->
                                <div class="mb-4 font-sm ">
                                    <label for="father-name" class="form-label"><b>Father Name:</b></label>
                                    <input type="text" class="form-control border" id="father-name"
                                        value="{{ old('name') }}"
                                        name="father_name"placeholder="Enter your father's name">
                                </div>
                                <div class="mb-4 font-sm ">
                                    <label for="contact" class="form-label"><b>Contact No:</b></label>
                                    <input type="number" class="form-control border" id="contact"
                                        value="{{ old('contact_no') }}"
                                        name="contact_no"placeholder="Enter your Phone Number">

                                </div>
                                <div class="mb-4 font-sm ">
                                    <label for="email" class="form-label"><b>Email:</b></label>
                                    <input type="email" class="form-control border" id="email"
                                        value="{{ old('email') }}" name="email"placeholder="Enter your Email">

                                </div>
                                <div class="mb-4 font-sm ">
                                    <label for="cnic" class="form-label"><b>Student CNIC/Bform Number:</b></label>
                                    <input type="number" class="form-control border" id="cnic"
                                        value="{{ old('cnic') }}" name="cnic"placeholder="Enter your CNIC number">

                                </div>

                                <!--newfields end-->

                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Parents ID Card:</b></label>
                                    <input type="file" class="form-control" id="parents_id_card"
                                        name="parents_id_card" accept="application/pdf" required
                                        value="{{ old('parents_id_card') }}">
                                    @error('parents_id_card')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Recent Electricity Bills:</b></label>
                                    <input type="file" class="form-control" id="electricity_bills"
                                        name="electricity_bills" accept="application/pdf" required
                                        value="{{ old('electricity_bills') }}">
                                    @error('electricity_bills')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Recent Parents's Salary
                                            Slip:</b></label>
                                    <input type="file" class="form-control" id="salary_slip" name="salary_slip"
                                        accept="application/pdf" required value="{{ old('electricity_bills') }}">
                                    @error('electricity_bills')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Most Recent Academic
                                            Transcripts:</b></label>
                                    <input type="file" class="form-control" accept="application/pdf"
                                        id="academic_transcripts" name="academic_transcripts" required
                                        value="{{ old('academic_transcripts') }}">
                                    @error('academic_transcripts')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Parental Bank Maintenance
                                            Certificate:</b></label>
                                    <input type="file" class="form-control"
                                        value="{{ old('parental_bank_certificate') }}" id="file"
                                        name="parental_bank_certificate" accept="application/pdf" required multiple>
                                    @error('parental_bank_certificate')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4 font-sm">
                                    <label for="file" class="form-label"><b>Achievements & Awards/ If
                                            Any:</b></label>
                                    <input type="file" class="form-control" id="file"
                                        name="achievements_and_awards[]" accept="application/pdf"
                                        value="{{ old('achievements_and_awards') }}" multiple>
                                </div>
                                <div class="mb-3 font-sm">
                                    <label for="message" class="form-label"><b>Need Assistance? Send Us a Message.</b></label>
                                    <textarea class="form-control" id="message" name="message" rows="3" placeholder="Type your message...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 font-sm">
                                    <label for="contact" class="mt--20 mb--20"><span class="color-one">*</span>
                                        <input type="checkbox" name="policy" class="policy-checkbox"> By clicking
                                        'Submit', you confirm that the information provided is accurate and complete to
                                        the best of your knowledge. You agree to be bound by our <a
                                            href="https://escienceacademy.com/terms-conditions"><b>Terms and Conditions</b></a> and acknowledge that you have
                                        read our <a href="https://escienceacademy.com/privacy-policy"><b>Privacy Policy</b></a></label>
                                    @error('policy')
                                        <span class="error" style="color: red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn custom-btn-font btn-primary p-3 w-100">Submit</button>
                            </form>
                        </div>
                    </div>

                </div>
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
