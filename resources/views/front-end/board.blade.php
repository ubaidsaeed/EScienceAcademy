<div>

    <div class="cs-breadcrumb-area background-common custom-breadcumb"
        style="background-image: url(https://theme-coderstation.com/kan/assets/images/banner/breadcrumb-bg.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- bread crumb inner wrapper -->
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">{{ ucfirst($board->name) }}</h2>
                        <div class="meta">
                            <span><a href="/">Home </a> / 
                                <a href="javascript:void(0)">Board </a> /
                                <a
                                    href="javascript:void(0)">{{ ucfirst($board->name) }}</a>
                                
                            </span>
                        </div>
                    </div>
                    <!-- bread crumb inner wrapper end -->
                </div>
            </div>
        </div>
        <div class="breadcrumb-shape-img">
            <img class="one floating-up-3" src="https://theme-coderstation.com/kan/assets/images/footer/dotted-03.svg"
                alt="">
            <img class="two" src="assets/images/footer/leaf.svg" alt="">
            <img class="three" src="https://theme-coderstation.com/kan/assets/images/banner/four-round.svg"
                alt="">
            <img class="four wow fadeIn" data-wow-delay=".55s"
                src="	https://theme-coderstation.com/kan/assets/images/footer/round.svg" alt="">
            <img class="five" src="https://theme-coderstation.com/kan/assets/images/footer/leaf-2.svg" alt="">
            <img class="six floating-up-3" src="assets/images/footer/dotted-04.svg" alt="">
            <img class="seven" src="https://theme-coderstation.com/kan/assets/images/footer/round2.svg" alt="">
        </div>
    </div>

    <!-- Start Service Details Area -->
    <section class="cs-service-details-area">
        <div class="container p-0 pl_sm--15 pr_sm--15">
            <div class="row p-0">
                <div class="col-l2 p-0">
                    <div class="course-tabs">
                        <div class="tab-buttons">
                            @foreach($pageRecord as $index => $item)
                             <button class="custom-tab-btn @if($index == 0) active @endif" data-tab="{{ $item->slug }}">
                                {{$item->name}}</button> 
                            @endforeach
                            </div>
                            @foreach($pageRecord as $index => $item)
                        <div class="custom-tab-content @if($index == 0) active @endif" " id="{{ $item->slug }}">
                            <h2 class="title-2">{{$item->name }}</h2>
                           {!! $item->content !!}
                        </div>
                        @endforeach
                        {{-- <div class="custom-tab-content" id="details">
                            <h2 class="section-title text-capitalize mt--40">Course Description</h2>
                            <p>Maecenas sed tortor molestie, sagittis nibh sit amet, dapibus felis. Vivamus sed neque
                                ultrices nulla eu, venenatis dui. Praesent luctus urna eu dapibus pulvinar Curabitur
                                accumsan.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque iaculis mi id lacinia
                                tempor. Etiam venenatis ornare magna eu sodales. Mauris vitae iaculis turpis. Curabitur
                                non sollicitudin justo. Donec ipsum urna, eleifend a vehicula at, blandit nec ligula.
                                Donec suscipit urna ac scelerisque interdum. In pulvinar volutpat sapien, vitae blandit
                                magna.</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- End Service Details Area -->
</div>


