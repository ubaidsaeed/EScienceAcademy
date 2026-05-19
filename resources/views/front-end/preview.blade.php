@extends('layouts.frontend.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Preview Banner -->
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fa fa-eye fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">Page Preview</h5>
                        <p class="mb-0">This is a preview of how your page will look to visitors.</p>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <article class="page-content">
                @if($page->thumbnail)
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/pages/' . $page->thumbnail) }}" 
                         alt="{{ $page->title }}" 
                         class="img-fluid rounded" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
                @endif
                
                <h1 class="page-title mb-4">{{ $page->title }}</h1>
                
                @foreach($page->sections as $section)
                    @switch($section->content_type)
                        @case(1)
                            <!-- Rich Text Content -->
                            <div class="section-content mb-5">
                                {!! $section->page_data !!}
                            </div>
                            @break
                            
                        @case(2)
                            <!-- Subjects Section -->
                            <div class="section-subjects mb-5">
                                <h3 class="section-title mb-4">Subjects</h3>
                                <div class="row">
                                    <!-- Add your subjects display logic here -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fa fa-book fa-3x text-primary mb-3"></i>
                                                <h5>Subject Name</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break
                            
                        <!-- Add other content types as needed -->
                        
                        @default
                            <div class="alert alert-secondary mb-5">
                                <p>Section type: {{ $section->content_type }}</p>
                            </div>
                    @endswitch
                @endforeach
            </article>
            
            <!-- Back to Edit Button (for admin) -->
            @auth
            @if(auth()->user()->can('edit pages'))
            <div class="mt-5 pt-4 border-top text-center">
                <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit me-2"></i> Edit This Page
                </a>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection