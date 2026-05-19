@php
    $setting = \App\Models\Setting::first(); // ✅ Fetch only one record
    $footerRecord = $setting && $setting->footer_quick_links ? json_decode($setting->footer_quick_links, true) : [];
    $address = $setting->address ?? '';
    $social_links = $setting && $setting->social_links ? json_decode($setting->social_links, true) : [];
    $whatsapp = $setting->whatsapp_contact ?? '';
    $mail = $setting->contact_mail ?? '';
@endphp
<style>
    .whatsapp-left {
    position: fixed;
    bottom: 100px;
    right:20px;
    z-index: 9999;
    background-color: #25D366;
    border-radius: 50%;
    padding: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.whatsapp-left:hover {
    transform: scale(1.1);
}

.whatsapp-btn svg {
    display: block;
}

</style>
<!-- Start Footer Area -->
<footer class="cs-footer-area footer-2 inner background-common"
    style="background-image: url({{ asset('build/assets/frontend/images/footer/ct-footer.png') }});"
    id="footer">
    <div class="container">
        <div class="cs-footer-newsletter-area">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="newsletter-content-area">
                        <h2 class="title text-anim">Subscribe <br>For Update</h2>
                        <p class="desc text-anim">Stay in touch with us to get latest news</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <form  action="{{ route('contact-us') }}" method="post" class="newsletter-form-area wow fadeInRight" data-wow-delay=".35s"
                        id="emailForms">
                        @csrf
                        <!--<div class="cs-footer-contact">-->
                        <!--    <p class="text">Contact Mail </p>-->

                        <!--    <a href="mailto:{{ trim($mail) }}" class="mail">{{ e($mail) }}</a>-->
                        <!--</div>-->
                        <div class="single__wrapper mt--80">
                              <input type="hidden" name="type" value="newletter">
                            <input type="email" name="email" placeholder="Enter your email address..." required>
                            <button type="submit" class="faq-btn rounded-button bg-four">
                                <!--<svg width="19" height="18" viewBox="0 0 19 18" fill="none"-->
                                <!--        xmlns="http://www.w3.org/2000/svg">-->
                                <!--        <path d="M1 17L17 1H7.8" stroke="white"></path>-->
                                <!--    </svg>-->
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="cs-footer-inner">
            <div class="footer-logo-area perspective wow zoomIn" data-wow-delay=".45s">
                 <a class="logo-light d-flex custom-center-class" href="{{ route('home') }}"><img
                                class="footer-logo rotate-footer-logo"
                                src="{{ asset('build/assets/frontend/images/escience-assets/escience-icon-logo.svg') }}"
                                alt="cs">
                                <img
                                class="footer-logo-text"
                                src="{{ asset('build/assets/frontend/images/escience-assets/escience-text-logo.svg') }}"
                                alt="cs"></a>
            </div>
            <div class="footer-content-area">
                <div class="single-widget wow fadeInRight" data-wow-delay=".55s">
                    <h4 class="heading-title">Explore Links</h4>
                    <ul class="single-links">
                        @foreach ($footerRecord as $link)
                            @if (isset($link['title']) && isset($link['slug']))
                                {{-- <li><a href="{{ route('frontend.page', $link['slug']) }}">{{ $link['title'] }}</a></li> --}}
                            @endif
                        @endforeach
                          <li><a target="_blank" href="https://www.britishcouncil.pk/sites/default/files/exam_registration_process_guideline_mj2023_private_candidates.pdf">CAIE Registration</a></li>
                    </ul>
                </div>
                <div class="address-area wow fadeInRight" data-wow-delay=".65s">
                    <div class="content">
                        <h4 class="heading-title">Connect</h4>
                        <p class="desc"> <i class="fa-regular fa-phone"></i><a href="tel:+{{$whatsapp}}" class="tel"> {{$whatsapp}} </a><br> <i class="fa-regular fa-envelope"></i> <a href="mailto:{{ trim($mail) }}" class="mail">{{ e($mail) }}</a></p>
                       
                    </div>
                    <div class="social-area">
                        <ul>
                            @foreach ($social_links as $index => $link)
                                @if (isset($link['platform']) && isset($link['url']))
                                    <li><a class="bg-{{ ['one', 'two', 'three', 'four'][$index % 4] }}"
                                            href="{{ $link['url'] }}" target="_blank"><i
                                                class="fa-brands fa-{{ strtolower($link['platform']) == 'facebook' ? 'facebook-f' : strtolower($link['platform']) }}">
                                            </i></a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="logo-shape-area">
        <!--<img class="footer-logo" src="{{ asset('build/assets/frontend/images/footer/logo-shape.svg') }}" alt="footer-logo">-->
    </div>

    <div class="footer-shape-img">
            <!--<img class="two" src="{{ asset('build/assets/frontend/images/footer/leaf.svg') }}" alt="">-->
            <!--<img class="four wow fadeIn" data-wow-delay=".55s" src="{{ asset('build/assets/frontend/images/footer/round.svg') }}" alt="">-->
            <!--<img class="five" src="{{ asset('build/assets/frontend/images/footer/leaf-2.svg') }}" alt="">-->
            
        </div>

    <div class="cs-footer-copyright-area bg-white">
            <div class="copyright-content">
                <p class="title">All Rights Reserved Developed By: <a href="javascript:void(0)">LiveBits</a></p>
            </div>
        </div>
</footer>
<!-- End Footer Area -->
<!-- End Footer Area -->

<!-- Start Scricpt Area -->
    <div class="cs-cursor cursor-outer" data-default="yes" data-link="yes" data-slider="no">
        <span class="fn-cursor"></span>
    </div>
    <div class="cs-cursor cursor-inner" data-default="yes" data-link="yes" data-slider="no">
        <span class="fn-cursor">
    <span class="fn-left"></span>
        <span class="fn-right"></span>
        </span>
    </div>
    <!--scroll top button-->
    <button class="scroll-top-btn">
        <i class="fa-regular fa-angles-up"></i>
    </button>
    <!--scroll top button end-->

    <!--Preloader Start-->
    <div id="kan-loader">
        <div class="loader-wrapper">
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!--Preloader end-->


    
<!--whatapps -->

<div class="whatsapp-left">
    <a href="https://wa.me/+{{ $whatsapp }}/?text=hello" target="_blank" class="whatsapp-btn">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="68" height="68" viewBox="0 0 48 48"
            style=" fill:#000000;">
            <path fill="#fff"
                d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z">
            </path>
            <path fill="#fff"
                d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z">
            </path>
            <path fill="#cfd8dc"
                d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z">
            </path>
            <path fill="#40c351"
                d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z">
            </path>
            <path fill="#fff" fill-rule="evenodd"
                d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                clip-rule="evenodd"></path>
        </svg>
    </a>
</div> 

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
<script>
    setTimeout(function() {
        $('.message').fadeOut('fast');
    }, 5000);
    document.getElementById("emailForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form from refreshing the page

        const emailInput = document.getElementById("emailInput");
        const email = emailInput.value.trim();

        if (email) {
            // Open default mail client
            window.location.href = `mailto:${email}?subject=Your%20Subject%20Here&body=Your%20Message%20Here`;

            // Optional: Clear input field
            emailInput.value = '';
        } else {
            alert("Please enter a valid email address.");
        }
    });
</script>
