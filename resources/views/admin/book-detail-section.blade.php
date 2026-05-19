<div>
    <style>
        .item-card{
            height: 255px;
        }
        i.fa-solid.fa-folder {
    font-size: 100px;
    display: flex;
    justify-content: center;
}
    </style>
    <div>
        <div>
                    <!--Page header-->
                    <div class="page-header d-lg-flex d-block">
                        <div class="page-leftheader">
                            <div class="page-title">MCQS Section</div>
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
                                            <i class="fa-solid fa-folder"></i>
                                            <div class="product-overly">
                                                <a href="{{route('admin.student.chapter.section')}}" class="text-white"><i class="fa fa-eye me-1"></i><span>View</span></a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <a class="shop-title">AS Organic Chem Book 1</a>
                                            <div class="mb-1 fs-13 mt-2">
                                            </div>
                                            <div class="cardprice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div class="card item-card overflow-hidden">
                                        <div class="item-image">
                                                <i class="fa-solid fa-folder"></i>

                                            <div class="product-overly">
                                                <a href="{{route('admin.student.chapter.section')}}" class="text-white"><i class="fa fa-eye me-1"></i><span>View</span></a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <a class="shop-title">AS Organic Chem Book 2</a>
                                            <div class="mb-1 fs-13 mt-2">
                                            </div>
                                            <div class="cardprice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div class="card item-card overflow-hidden">
                                        <div class="item-image">
                                            <i class="fa-solid fa-folder"></i>
                                            <div class="product-overly">
                                                <a href="{{route('admin.student.chapter.section')}}" class="text-white"><i class="fa fa-eye me-1"></i><span>View</span></a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <a class="shop-title">AS Organic  Physical chemistry Book 1 MCQS</a>
                                            <div class="mb-1 fs-13 mt-2">
                                            </div>
                                            <div class="cardprice">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                    <div class="card item-card overflow-hidden">
                                        <div class="item-image">
                                            <i class="fa-solid fa-folder"></i>
                                            <div class="product-overly">
                                                <a href="{{route('admin.student.chapter.section')}}" class="text-white"><i class="fa fa-eye me-1"></i><span>View</span></a>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <a class="shop-title">AS Organic Physical chemistry Book 2</a>
                                            <div class="mb-1 fs-13 mt-2">

                                            </div>
                                            <div class="cardprice">
                                                {{-- <span class="type--strikethrough">P</span>
                                                <span>PKR:3000</span> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- End  Row -->
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