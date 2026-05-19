@extends('layouts.admin.app')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Content: {{ $package->name }}</h1>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6>Subjects</h6>
                </div>
                <div class="list-group">
                    @foreach($package->subjects as $subject)
                    <a href="#" class="list-group-item list-group-item-action subject-item" 
                       data-subject-id="{{ $subject->id }}">
                        <div class="d-flex justify-content-between">
                            <span>{{ $subject->name }}</span>
                            <span class="badge bg-success">₹{{ $subject->pivot->subject_price }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6>Access Summary</h6>
                </div>
                <div class="card-body" id="summary">Loading...</div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 id="tree-title">Select a subject to manage content</h6>
                    <div>
                        <button class="btn btn-success btn-sm" id="select-all">Select All</button>
                        <button class="btn btn-danger btn-sm" id="deselect-all">Deselect All</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="jstree"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
<style>
    .jstree-themeicon { margin-right: 8px; }
    .jstree-anchor { padding: 4px 8px !important; }
    #jstree { font-size: 14px; }
</style>
@endpush

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

<script>
let currentSubjectId = null;
const packageId = {{ $package->id }};

function loadAccessSummary() {
    $.get("{{ route('admin.packages.access-summary', $package->id) }}")
        .done(function(response) {
            // Existing HTML rendering...
            let folderPercentage = response.total_folders > 0 ? Math.round((response.accessible_folders / response.total_folders) * 100) : 0;
            // ... rest unchanged
        })
        .fail(function(xhr) {
            console.error('Summary Error:', xhr.responseJSON);
            $('#access-summary').html('<div class="alert alert-danger">Error loading summary. Check logs.</div>');
        });
}

function loadTree() {
    $('#jstree').jstree('destroy').empty();
    $('#tree-title').text('Loading...');

    $.get("{{ route('admin.packages.content', ['packageId' => ':id', 'subjectId' => ':sid']) }}"
        .replace(':id', packageId).replace(':sid', currentSubjectId), function(data) {
        
        $('#jstree').jstree({
            'core': {
                'data': data,
                'themes': { stripes: true }
            },
            'plugins': ['wholerow', 'checkbox', 'types'],
            'checkbox': { three_state: false, cascade: 'up+down' }
        });

        $('#tree-title').text('Content Tree - Click to select/deselect');
    });
}

$(document).on('click', '.subject-item', function(e) {
    e.preventDefault();
    currentSubjectId = $(this).data('subject-id');
    $('.subject-item').removeClass('active');
    $(this).addClass('active');
    loadTree();
    loadSummary();
});

$('#select-all').click(() => $('#jstree').jstree('select_all'));
$('#deselect-all').click(() => $('#jstree').jstree('deselect_all'));

$('#jstree').on('changed.jstree', function (e, data) {
    const selected = data.instance.get_selected(true);
    const toSync = { folders: [], files: [] };

    selected.forEach(node => {
        if (node.id.startsWith('folder_')) {
            toSync.folders.push(node.id.replace('folder_', ''));
        } else if (node.id.startsWith('file_')) {
            toSync.files.push(node.id.replace('file_', ''));
        }
    });

    // Send bulk update (you can optimize further with debounce)
    $.post("{{ route('admin.packages.content.toggle', $package->id) }}", {
        _token: '{{ csrf_token() }}',
        checked: data.selected.length > 0,
        id: data.node.id
    });
});

$(document).ready(function() {
    loadSummary();
    if ($('.subject-item').length > 0) {
        $('.subject-item').first().click();
    }
});
</script>
@endpush