<section id="advisor" class="advisor-area default-padding bottom-less">
    <div class="container">
        <div class="advisor-items text-center text-light">
            <div class="row">
                <!-- Single Item -->
                @isset($team)
                @foreach($team as $index =>$items)
                <div class="col-lg-4 col-md-6 single-item">
                    <div class="advisor-item">
                        <div class="info-box">
                            <div class="editable-image">
                                <img src="{{ asset($items->image) }}" alt="Thumb"
                                    class="advisor-img" data-name="advisor-1-image">
                            </div>
                            <div class="info-title">
                                <h4 class="editable-text" contenteditable="true" data-name="advisor-1-name" style="color:black !important">{{$items->name}}</h4>
                                <span class="editable-text" contenteditable="true" data-name="advisor-1-title">{{$items->designation}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endisset
                <!-- Repeat similar blocks for other advisors -->
            </div>
        </div>
    </div>
</section>