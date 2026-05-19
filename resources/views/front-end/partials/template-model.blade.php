
<!-- Modal template use -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Template Section</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Assessment Boards</h5>
                                </div>
                                <img src="{{asset('build/images/borads.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template701">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template701collapse">
                            <div class="card">
                                <div class="card-body" id="template701">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.assessment-boards')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Preparatory Subjects</h5>
                                </div>
                                <img src="{{asset('build/images/subjects.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template702">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template702collapse">
                            <div class="card">
                                <div class="card-body" id="template702">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.subjects')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    {{-- <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Select the Approperiate Package</h5>
                                </div>
                                <img src="{{asset('build/images/packages.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template703">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template703collapse">
                            <div class="card">
                                <div class="card-body" id="template703">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.package')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    <li>
                        <div class="row">
                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Some of our Achievements</h5>
                                </div>
                                <img src="{{asset('build/images/achievements.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5" data-content="template704">Use
                                    Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template704collapse">
                            <div class="card">
                                <div class="card-body" id="template704">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.achievements')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!---->
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Best of our Counselling</h5>
                                </div>
                                <img src="{{asset('build/images/counselling.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template706">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template706collapse">
                            <div class="card">
                                <div class="card-body" id="template706">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.counselling')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>We Provided Case study</h5>
                                </div>
                                <img src="{{asset('build/images/casestudy.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template707">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template707collapse">
                            <div class="card">
                                <div class="card-body" id="template707">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.case-study')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>We Provide Frequency & question's</h5>
                                </div>
                                <img src="{{asset('build/images/faqs.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template708">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template708collapse">
                            <div class="card">
                                <div class="card-body" id="template708">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('livewire.admin.include.faqs')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                     {{-- <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Soft Skill Template</h5>
                                </div>
                                <img src="{{asset('images/template/soft.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template709">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template709collapse">
                            <div class="card">
                                <div class="card-body" id="template709">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('backend.pages.templates.soft-skill')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Tabs Template</h5>
                                </div>
                                <img src="{{asset('images/template/tabs.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template710">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template710collapse">
                            <div class="card">
                                <div class="card-body" id="template710">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('backend.pages.templates.tabs')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="row">

                            <div class="col-8 my-6">
                                <div class="col-sm-12 text-center">
                                    <h5>Multi Tabs Template</h5>
                                </div>
                                <img src="{{asset('images/template/tabs.PNG')}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-4 col-lg-4 buttonDiv my-6 mb-0">
                                <button role="button" class="btn btn-success custom my-5"
                                    data-content="template711">Use Template</button>
                            </div>
                        </div>
                        <div class="row collapse" id="template711collapse">
                            <div class="card">
                                <div class="card-body" id="template711">
                                    <section
                                        class="dynmicSec-p80 fixHeight75  d-flex align-items-center dynamic-bg-class">
                                        <div class="container">
                                            @include('backend.pages.templates.multitabs')
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>