
// jquery data table
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';
    let CsrfToken = $('meta[name="csrf-token"]').attr('content');
     $('#details-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/user/permissions/show",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': CsrfToken
            },
            error: function(xhr, error, thrown) {
                console.log('Ajax error:', error);
                if (xhr.status === 401 || xhr.status === 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unauthorized',
                        text: 'You are not authorized to view permissions.'
                    });
                }
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { data: 'name', name: 'name' },
            { 
                data: 'action', 
                name: 'action',
                orderable: false,
                searchable: false,
                width: '100px'
            }
        ],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        responsive: true,
        order: [[1, 'asc']],
        language: {
            emptyTable: "No roles found",
        }
    });
});


function addPermission() {
    let permissionName = $("#permissionName").val().trim();
    let saveBtn = $("#save");
    
    // Validation
    if (!permissionName) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: 'Please enter name',
            customClass: {
                container: 'swal2-high-zindex'
            },
             didOpen: () => {
                    // Ensure proper z-index when opening
                    $('.swal2-container').css('z-index', '999999');
                }
        });
        return;
    }
    
    saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    
    $.ajax({
        url: "/user/permissions/store",
        type: "POST",
        data: {
            name: permissionName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $("#createPermission").modal('hide');
            $("#permissionName").val('');
            $('#details-datatable').DataTable().ajax.reload();
            
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                timer: 2000,
                showConfirmButton: false,
                 
            });
        },
        error: function(xhr) {
            let errorMessage = 'Something went wrong!';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = xhr.responseJSON.errors.name[0];
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            saveBtn.prop('disabled', false).html('Save');
        }
    });
}
// edit permission
function edit(id) {
    let url = "/user/permissions/edit";
    let object = {
        id: id,
        _token: document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"), // Include CSRF token
    };

    let editData = JSON.stringify(object);
    let xhr = new XMLHttpRequest();

    xhr.open("post", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(editData);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                document.getElementById("updateName").value = response.name;
                document.getElementById("updateId").value = response.id;
            } else {
                console.error("Error: " + xhr.status);
            }
        }
    };
}
// update

function updatePermission() {
    let name = $("#updateName").val().trim();
    let id = $("#updateId").val();
    let updateBtn = $("#update");
    
    if (!name) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: 'Please enter permission name'
        });
        return;
    }
    
    updateBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    
    $.ajax({
        url: "/user/permissions/update",
        type: "POST",
        data: {
            id: id,
            name: name,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $("#updateModel").modal('hide');
            $('#details-datatable').DataTable().ajax.reload();
            
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            let errorMessage = 'Failed to update permission';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = xhr.responseJSON.errors.name[0];
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            updateBtn.prop('disabled', false).html('Update');
        }
    });
}

// delete data

function deleteds(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let table = $("#details-datatable").DataTable();
            let object = {
                id: id,
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            };
            let deleted = JSON.stringify(object);

            let url = "/user/permissions/destroy";
            xhr = new XMLHttpRequest();
            xhr.open("post", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(deleted);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        table.ajax.reload();
                        Swal.fire({
                            title: "Deleted!",
                            text: response.success,
                            icon: "success",
                        });
                    }
                }
            };
        }
    });
}
