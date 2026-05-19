@php
    $setting = \App\Models\Setting::first(); // ✅ Fetch only one record
    $address = $setting->address ?? '';
    $mail = $setting->contact_mail ?? '';

    $segment = request()->segment(3) == 'custom-query';
    $level = request()->segment(2);
    $levelCapital = strtoupper($level);
    $boards = Illuminate\Support\Facades\DB::table('boards')->where('slug', 'caie')->get();
    $levels = Illuminate\Support\Facades\DB::table('levels')->where('slug', $level)->first();
    // $subjects  = Illuminate\Support\Facades\DB::table('subjects')
    // ->join('subject_boards' ,as 'subjects.')
    // ->where('level_id',$levels->id)->get();
    $subjects = DB::table('subjects')
        ->join('subject_boards', 'subjects.id', '=', 'subject_boards.subject_id')
        ->where('subject_boards.level_id', $levels->id)
        ->select('subjects.*') // Select only subject columns
        ->get();
@endphp
@push('style')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet">
@endpush
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
        <div class="section-inner mt--0">
            <div class="map-area">
                @if (!$segment)
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d5630.974780877466!2d90.41411207625713!3d23.77463529323725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1698665848650!5m2!1sen!2sbd&amp;z=12"
                        height="800" style="border:0;"></iframe>
                @endif
            </div>
            <div class="contact-form bg-white">
                <div class="section__title__area text-center mb--50">
                    <p class="section__title__area__sub-title m-auto mb--15 color-one wow fadeInUp"
                        data-wow-delay=".25s"
                        style="visibility: visible; animation-delay: 0.25s; animation-name: fadeInUp;">Quality Education
                    </p>
                    <h2 class="section__title__area__title text-anim" style="perspective: 400px;">
                        <div
                            style="display: block; text-align: center; position: relative; translate: none; rotate: none; scale: none; transform-origin: 318px 31px; transform: translate3d(0px, 0px, 0px); opacity: 1;">
                            @if ($segment)
                                Custom Query
                            @else
                                Contact Us
                            @endif
                        </div>
                    </h2>
                </div>
                <form
                    action="@if ($segment) {{ route('subscription.custom') }} @else {{ route('contact-us') }} @endif"
                    method="post">
                    @csrf
                    <div class="single-input-wrapper">
                        <input type="text" name="name" placeholder="Enter Your Name" value="{{ old('name') }}"
                            required="">
                        @error('name')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                        <input type="hidden" name="type" value="contact-us">
                    </div>
                    <div class="single-input-wrapper">
                        <input type="email" name="email" placeholder="infoflex@info.com" value="{{ old('email') }}"
                            required="">


                        @error('email')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($segment)
                        <div class="single-input-wrapper">
                            <select id="subject single-input-wrapper" name="board" class="custom-select">
                                @foreach ($boards as $board)
                                    <option value="{{ $board->name }}">{{ $board->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="single-input-wrapper">
                            <select id="subject single-input-wrapper" name="level" class="custom-select">
                                {{-- @foreach ($subjects as $level) --}}
                                <option value="{{ $level }}">{{ $levelCapital }}</option>
                                {{-- @endforeach --}}
                            </select>
                        </div>

                        <div class="single-input-wrapper">
                            <select id="course" name="subject[]" multiple>
                                @foreach ($subjects as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>


                    @endif
                    <div class="single-input-wrapper">
                        <textarea name="message" id="contact" placeholder="Write your message" data-qb-tmp-id="lt-86304" spellcheck="false"
                            data-gramm="false">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>


                    <button type="submit" class="form-btn mt--40 ">
                        <span class="rounded-button bg-one">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
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


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $(function() {
        $('select').each(function() {
            $(this).select2({
                theme: 'bootstrap4',
                width: 'style',
                placeholder: $(this).attr('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        });
    });
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
