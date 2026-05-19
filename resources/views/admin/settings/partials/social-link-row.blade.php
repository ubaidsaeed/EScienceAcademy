<div class="row mb-2 dynamic-form-row" id="social-row-{{ $index }}">
    <div class="col-lg-5">
        <input type="text" class="form-control" 
               name="social_links[{{ $index }}][platform]" 
               placeholder="Platform (e.g., Facebook)">
    </div>
    <div class="col-lg-6">
        <input type="url" class="form-control" 
               name="social_links[{{ $index }}][url]" 
               placeholder="Profile URL">
    </div>
    <div class="col-lg-1 remove-row-btn">
        <button type="button" class="btn btn-danger remove-row" 
                data-target="social-row-{{ $index }}">✖</button>
    </div>
</div>