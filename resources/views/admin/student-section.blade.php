<div>
    <style>
        i.fa-solid.fa-folder {
            display: flex;
            justify-content: center;
            font-size: 100px;
        }
    </style>
            <!--Page header-->
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <div class="page-title">Subjects</div>
                </div>
                <div class="page-rightheader ms-md-auto">
                    <div class=" btn-list">
                        {{-- <button class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title=""
                            data-bs-original-title="E-mail"> <i class="feather feather-mail"></i> </button>
                        <button class="btn btn-light" data-bs-placement="top" data-bs-toggle="tooltip" title=""
                            data-bs-original-title="Contact"> <i class="feather feather-phone-call"></i> </button>
                        <button class="btn btn-primary" data-bs-placement="top" data-bs-toggle="tooltip" title=""
                            data-bs-original-title="Info"> <i class="feather feather-info"></i> </button> --}}
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">

                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <div class="card item-card overflow-hidden">
                                <div class="item-image">
                                    <span class="badge badge-primary">Subscribe</span>
                                    <i class="fa-solid fa-folder"></i>
                                    <div class="product-overly">
                                        <a href="{{url('/course/detail')}}" class="text-white"><i
                                                class="fa fa-eye me-1"></i><span>Quick View</span></a>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <a class="shop-title">AS</a>
                                    <div class="mb-1 fs-13 mt-2">
                                        {{-- <a href="javascript:void(0);"><i class="fa fa-star text-yellow"></i></a>
                                        <a href="javascript:void(0);"><i class="fa fa-star text-yellow"></i></a>
                                        <a href="javascript:void(0);"><i class="fa fa-star text-yellow"></i></a>
                                        <a href="javascript:void(0);"><i class="fa fa-star-half-o text-yellow"></i></a>
                                        <a href="javascript:void(0);"><i class="fa fa-star-o text-yellow"></i></a>
                                        <a href="javascript:void(0);" class="text-muted fs-13"> (48)</a> --}}
                                    </div>
                                    <div class="cardprice">
                                        <span class="type--strikethrough">PKR:5000</span>
                                        <span>PKR:3000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End  Row -->

        {{--
    </div>
</div> --}}

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