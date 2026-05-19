
@php
 $contact_record = \App\Models\ContactPage::first();
@endphp
    <!-- Start Contact Info
    ============================================= -->
    <div class="contact-info-area default-padding">
        <div class="container">
            <div class="contact-info">
                <!-- Start Contact Info -->
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="item" style="max-height: 240px;">
                            <div class="icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="info">
                                <h4>Call</h4>
                                <span>{{$contact_record->call_no}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="item" style="max-height: 240px;">
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info">
                                <h4>Address</h4>
                                <span>+{{$contact_record->address}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="item" style="max-height: 240px;">
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info">
                                <h4>Email</h4>
                                <span>{{$contact_record->contact_mail}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Contact Info -->

                <div class="seperator col-lg-12">
                    <span class="border"></span>
                </div>

                <!-- Start Maps & Contact Form -->
                <div class="maps-form">
                    <div class="row">
                        <div class="col-lg-6 maps">
                            <h3>Our Location</h3>
                                                
                            <div class="google-maps">
                                <iframe src="@isset($contact_record->location_link){{$contact_record->location_link}}@else https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3617.7872274569877!2d67.03623757391853!3d24.939317342100374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33f876b9acb5d%3A0xe6e18f71c3155015!2sTrade%20Testing%20Board!5e0!3m2!1sen!2s!4v1725074192416!5m2!1sen!2s @endisset" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                            </div>
                        </div>
                        <div class="col-lg-6 form">
                            <div class="heading">
                                <h3>Contact Us</h3>
                                        <p>
                                            {!! $contact_record->contact_description!!}
                                        </p>

                            </div>
                            <form action="{{ route('home.contact.store') }}" method="post">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <input class="form-control" id="name" name="name" placeholder="Name" type="text" value="{{ old('name') }}">
                                           @error('name')
                                            <span class="alert-error">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <input class="form-control" id="email" name="email" placeholder="Email*" type="email" value="{{ old('email') }}">
                                             @error('email')
                                            <span class="alert-error">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <input class="form-control" id="phone" name="phone" placeholder="Phone" type="text" value="{{ old('phone') }}" >
                                           @error('phone')
                                            <span class="alert-error">
                                                {{ $message }}
                                            </span>
                                            @enderror <span class="alert-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group">
                                            <input class="form-control" id="subject" name="subject" placeholder="Subject" type="text" value="{{ old('subject') }}" >
                                            @error('subject')
                                            <span class="alert-error">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group comments">
                                            <textarea class="form-control" id="comments" name="message" placeholder="Kindly Let Us Know, How we can assist you in 100 words or Less." value="{{ old('message') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit">
                                        Send Message <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                                <!-- Alert Message -->
                                <div class="col-lg-12 alert-notification">
                                    <div id="message" class="alert-msg"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Maps & Contact Form -->

            </div>
        </div>
    </div>
    <!-- End Contact Info -->
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    setTimeout(function() {
        $('.message').fadeOut('fast');
    }, 5000);
</script>
@if (session('message'))
    <div class="alert alert-successs message">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong></strong>{{ session('message') }}.
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
@endsection