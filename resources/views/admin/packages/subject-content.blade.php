@extends('layouts.admin.app')

@section('content')

    <div class="app-content main-content">
        <div class="side-app">
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            Manage Content: <strong>{{ $subject->name }}</strong>
            <small class="text-muted">in Package: {{ $package->name }}</small>
        </h3>
        <a href="{{ route('admin.packages.subjects', $package->id) }}" class="btn btn-secondary">
            Back to Subjects
        </a>
    </div>

    <div class="row">
        <!-- LEFT: Tree -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Select Folders & Files to Assign</h5>
                    <div>
                        <button type="button" onclick="$('#jstree').jstree('open_all')" class="btn btn-sm btn-info me-2">
                            Open All
                        </button>
                        <button type="button" onclick="$('#jstree').jstree('close_all')" class="btn btn-sm btn-secondary">
                            Close All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="jstree"></div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Preview + Save -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Assigned Items <span class="badge bg-primary" id="count-badge">0</span></h5>
                </div>
                <div class="card-body" id="assigned-list" style="min-height: 200px; max-height: 500px; overflow-y: auto;">
                    <p class="text-muted">Select items from the left tree.</p>
                </div>
                <div class="card-footer">
                    <button id="save-assignment" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Save Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<style>
    #jstree { font-size: 14px; }
    .jstree-themeicon { margin-right: 10px; }
    .jstree-anchor { padding: 6px 8px; border-radius: 4px; }
    .jstree-anchor:hover { background: #e3f2fd; }
    .badge { font-size: 1em; }
</style>
@endpush

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const treeData = @json($folderHierarchy);
        const preselected = @json($assignedIds ?? []);

        // Debug (remove later if you want)
        console.log('Tree Data:', treeData);
        console.log('Preselected:', preselected);

        $('#jstree').jstree({
            core: {
                data: treeData,
                themes: {
                    name: 'default',
                    dots: true,
                    icons: true,
                    stripes: true
                }
            },
            plugins: ['wholerow', 'checkbox'],
           checkbox: {
                three_state: false,     // Important: No auto-select parent
                cascade: '',            // Important: No cascade at all
                tie_selection: true
            }
        });

        // When tree is ready → pre-select assigned items
       // ALL FOLDERS CLOSED BY DEFAULT
        $('#jstree').on('ready.jstree', function () {
            $('#jstree').jstree('close_all');  // This line closes all folders

            // Pre-check assigned items (even if in closed folders)
            if (preselected.length > 0) {
                preselected.forEach(id => {
                    const node = $('#jstree').jstree('get_node', id);
                    if (node) {
                        $('#jstree').jstree('check_node', id);
                        // Optional: auto-open parent folders of preselected items
                        let parent = node.parent;
                        while (parent && parent !== '#') {
                            $('#jstree').jstree('open_node', parent);
                            parent = $('#jstree').jstree('get_node', parent).parent;
                        }
                    }
                });
            }

            
            updatePreview();
        });

        // When user clicks → update right panel
        $('#jstree').on('changed.jstree', function (e, data) {
            updatePreview();
        });

        // Update preview list + count
        function updatePreview() {
            const selected = $('#jstree').jstree('get_selected');
            const list = $('#assigned-list');
            const countBadge = $('#count-badge');

            list.empty();
            countBadge.text(selected.length);

            if (selected.length === 0) {
                list.html('<p class="text-muted">No items selected.</p>');
                return;
            }

            selected.forEach(id => {
                const node = $('#jstree').jstree('get_node', id);
                if (node) {
                    list.append(
                        `<div class="badge bg-primary m-1 p-2 d-inline-flex align-items-center">
                            <i class="${node.icon || 'fa fa-file'} me-2"></i>
                            <span>${node.text}</span>
                        </div>`
                    );
                }
            });
        }

        // Save button
        $('#save-assignment').on('click', function () {
            const btn = $(this);
            const selected = $('#jstree').jstree('get_selected');

            if (selected.length === 0) {
                toastr.warning('Please select at least one item.');
                return;
            }

            btn.prop('disabled', true).html('Saving...');

            $.post("{{ route('admin.packages.subjects.content.save', [$package->id, $subject->id]) }}", {
                _token: '{{ csrf_token() }}',
                content_ids: selected
            })
            .done(function () {
                toastr.success('Content assigned successfully!');
            })
            .fail(function () {
                toastr.error('Failed to save. Please try again.');
            })
            .always(function () {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Assignment');
            });
        });
    });
</script>
@endpush