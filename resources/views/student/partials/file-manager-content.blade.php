@php
    $viewMode = $viewMode ?? 'grid';
@endphp

@if($viewMode === 'grid')
    {{-- Grid View --}}
    <div class="row g-3" id="content-grid">
        @foreach($items as $item)
            @include('student.partials.file-manager-item', ['item' => $item, 'viewMode' => 'grid'])
        @endforeach
    </div>
@else
    {{-- List View --}}
    <div class="file-manager-list" id="content-list">
        <div class="list-header d-none d-md-flex mb-2 p-2 border-bottom">
            <div class="col-md-4"><strong>Name</strong></div>
            <div class="col-md-2"><strong>Type</strong></div>
            <div class="col-md-2"><strong>Progress</strong></div>
            <div class="col-md-4"><strong>Actions</strong></div>
        </div>
        @foreach($items as $item)
            @include('student.partials.file-manager-item', ['item' => $item, 'viewMode' => 'list'])
        @endforeach
    </div>
@endif

