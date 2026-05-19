<div class="">
    <style>
        i.fa-solid.fa-folder {
    font-size: 100px;
    display: flex;
    justify-content: space-evenly;
}
        </style>
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <div class="page-title">Course Detail</div>
    </div>
    <div class="page-rightheader ms-md-auto">
        <div class=" btn-list">
            <button class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="E-mail"> <i class="feather feather-mail"></i> </button>
            <button class="btn btn-light" data-bs-placement="top" data-bs-toggle="tooltip" title="" data-bs-original-title="Contact"> <i class="feather feather-phone-call"></i> </button>
            <button class="btn btn-primary" data-bs-placement="top" data-bs-toggle="tooltip" title="" data-bs-original-title="Info"> <i class="feather feather-info"></i> </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="row no-gutter">
                    <div class="col-xl-6 border-end">
                        <div class="row product-slider">
                            <div class="product-carousel pt-5 pb-5">
                                <div id="carousel" class="carousel slide" data-bs-ride="false">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <i class="fa-solid fa-folder"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="text-center">2 Elements</span>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card-body">
                            <h4 class="product-title">AS</h4>
                            <div class="rating mb-3">
                            </div>
                            <p class="product-description">Suspendisse quos? Tempus cras iure temporibus? Eu laudantium cubilia sem sem! Repudiandae et! Massa senectus enim minim sociosqu delectus posuere.</p>
                            <h6 class="price">Current Price: <span class="h3 ms-2">PKR:3000</span></h6>
                            <p class="text-muted"><strong>91%</strong> of Student enjoyed this Course! <strong>(87 votes)</strong></p>
                            <div class="">
                                <label class="form-label">Description:</label>
                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                                <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized .</p>
                                <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system.</p>
                            </div>
                            <div class="btn-list mt-5">
                               <center> <a href="{{route('admin.student.doard.section')}}"><button class="add-to-cart btn btn-success">Learn</button></center></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header  border-0">
                <div class="card-title">Duration</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 border-top table-bordered text-nowrap">
                        <tbody>
                            <tr>
                                <th scope="row">Start Date</th>
                                <td> 12-11-2024</td>
                            </tr>

                            <tr>
                                <th scope="row">End Date</th>
                                <td> 12-12-2024</td>
                            </tr>

                            <tr>
                                <th scope="row">Total Month</th>
                                <td>1</td>
                            </tr>
                            {{-- <tr>
                                <th scope="row">Sleeve</th>
                                <td>Half Sleeve</td>
                            </tr>
                            <tr>
                                <th scope="row">Fit</th>
                                <td>Regular</td>
                            </tr>
                            <tr>
                                <th scope="row">Fabric</th>
                                <td>Poly-Cotton</td>
                            </tr>
                            <tr>
                                <th scope="row">Style Code</th>
                                <td>RGJ-29761</td>
                            </tr>
                            <tr>
                                <th scope="row">Ideal For</th>
                                <td>Men</td>
                            </tr>
                            <tr>
                                <th scope="row">Brand Color</th>
                                <td>Light Blue</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class="card">
            <div class="card-header  border-0">
                <div class="card-title">Reviews</div>
            </div>
            <div class="card-body">
                <div class="card shadow-none border">
                    <div class="d-sm-flex p-5">
                        <div class="d-flex me-3">
                            <a href="javascript:void(0);"><img class="media-object brround avatar-lg" alt="64x64" src="../assets/images/users/16.jpg"> </a>
                        </div>
                        <div class="media-body">
                            <h5 class="mt-1 mb-1 font-weight-semibold">Have you using update version</h5>
                            <small class="text-muted"><i class="fa fa-calendar"></i> Jan 21 2021 <i class=" ms-3 fa fa-clock-o"></i> 13:00</small>
                            <p class="fs-13 mb-2 mt-1">
                               Lorem ipsum dolor sit amet, quis Neque porro quisquam est, nostrud exercitation ullamco laboris   commodo consequat.
                            </p>
                            <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fe fe-message-circle"></i> Comment</span></a>
                            <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fa fa-reply"></i> Reply</span></a>
                            <div class="sub-media d-sm-flex mt-5">
                                <div class="d-flex me-3">
                                    <a href="javascript:void(0);"><img class="media-object brround avatar-lg" alt="64x64" src="../assets/images/users/1.jpg"> </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="mt-1 mb-1 font-weight-semibold">Yes, sir</h5>
                                    <small class="text-muted"><i class="fa fa-calendar"></i> Jan 22 2021 <i class=" ms-3 fa fa-clock-o"></i> 09:00</small>
                                    <p class="fs-13 mb-2 mt-1">
                                       Lorem ipsum dolor sit amet, quis Neque porro quisquam est, nostrud exercitation ullamco laboris   commodo consequat.
                                    </p>
                                    <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fe fe-message-circle"></i> Comment</span></a>
                                    <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fa fa-reply"></i> Reply</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none border">
                    <div class="d-sm-flex p-5">
                        <div class="d-flex me-3">
                            <a href="javascript:void(0);"><img class="media-object brround avatar-lg" alt="64x64" src="../assets/images/users/11.jpg"> </a>
                        </div>
                        <div class="media-body">
                            <h5 class="mt-1 mb-1 font-weight-semibold">Send the related data</h5>
                            <small class="text-muted"><i class="fa fa-calendar"></i> Dec 10 2020 <i class=" ms-3 fa fa-clock-o"></i> 06:00</small>
                            <p class="fs-13 mb-2 mt-1">
                               Lorem ipsum dolor sit amet, quis Neque porro quisquam est, nostrud exercitation ullamco laboris   commodo consequat.
                            </p>
                            <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fe fe-message-circle"></i> Comment</span></a>
                            <a href="javascript:void(0);" class="me-2 mt-1"><span class="badge badge-light"><i class="fa fa-reply"></i> Reply</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="card">
            <div class="card-header  border-0">
                <div class="card-title">Comments</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="name1" placeholder="Your Name">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="example-textarea-input" rows="6" placeholder="Write Comment"></textarea>
                </div>
                <a href="javascript:void(0);" class="btn btn-primary">Send Reply</a>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('contextmenu', function(e) {
    e.preventDefault();  // Prevents the right-click menu from appearing
});
document.addEventListener('keydown', function(e) {
if (e.keyCode === 123) {  // F12
e.preventDefault();    // Prevent F12
}
if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) {  // Ctrl+Shift+I or Ctrl+Shift+J
e.preventDefault();    // Prevent opening DevTools
}
if (e.ctrlKey && e.keyCode === 85) {  // Ctrl+U
e.preventDefault();    // Prevent viewing page source
}
// Block Ctrl + C (Copy)
if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
e.preventDefault();
}
// Block Ctrl + S (Save)
if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
e.preventDefault();
}
// Prevent Print Screen (PrtScn) key
if (e.key === 'PrintScreen' || e.keyCode === 44) {
e.preventDefault();
}
});
</script>