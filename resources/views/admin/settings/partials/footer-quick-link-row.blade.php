<div class="row mb-2 dynamic-form-row" id="footer-row-{{ $index }}">
    <div class="col-lg-5">
        <input type="text" class="form-control" 
               name="footer_quick_links[{{ $index }}][title]" 
               placeholder="Link Title">
    </div>
    <div class="col-lg-6">
        <select class="form-control" name="footer_quick_links[{{ $index }}][slug]">
            <option value="">Select Page...</option>
            @foreach($pages as $page)
                <option value="{{ $page->slug }}">{{ $page->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-1 remove-row-btn">
        <button type="button" class="btn btn-danger remove-row" 
                data-target="footer-row-{{ $index }}">✖</button>
    </div>
</div>