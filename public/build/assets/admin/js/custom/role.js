// Global variable for CSRF token
const CsrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();
    
    // Initialize checkbox events after modal is shown
    $('#create').on('shown.bs.modal', function() {
        initializeCreateModalCheckboxes();
    });
    
    $('#update').on('shown.bs.modal', function() {
        initializeEditModalCheckboxes();
    });
    
    // Form submission prevention
    $('#createRoleForm').submit(function(e) {
        e.preventDefault();
        addRole();
    });
});

function initializeDataTable() {
    if (!$.fn.dataTable) {
        console.error('DataTables is not loaded!');
        return;
    }
    
    $('#details-datatable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, "desc"]],
        ajax: {
            url: "/user/role/show",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': CsrfToken
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable error:', error);
                if (xhr.status === 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Access Denied',
                        text: 'You are not authorized to view roles'
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
            { 
                data: 'name', 
                name: 'name',
            },
            { 
                data: 'action', 
                name: 'action',
                orderable: false,
                searchable: false,
                width: '100px'
            }
        ],
        language: {
            emptyTable: "No roles found",
        }
    });
}

function initializeCreateModalCheckboxes() {
    // Check all permissions in create modal
    $('#checkAllPermissions').off('change').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('#create .permission-checkbox').prop('checked', isChecked);
        $('#create .module-checkbox').prop('checked', isChecked);
    });
    
    // Module checkbox functionality for create modal
    $('#create .module-checkbox').off('change').on('change', function() {
        const module = $(this).data('module');
        const isChecked = $(this).prop('checked');
        $(`#create .isscheck_${module}`).prop('checked', isChecked);
    });
    
    // Update module checkbox based on individual permissions
    $('#create .permission-checkbox').off('change').on('change', function() {
        updateModuleCheckbox(this);
    });
}


// Add Role function
function addRole() {
    // Validate form
    if (!validateCreateForm()) {
        return;
    }
    
    const roleName = $('#roleName').val().trim();
    const permissions = [];
    
    // Collect checked permission IDs
    $('#create .permission-checkbox:checked').each(function() {
        permissions.push($(this).val());
    });
    
    // Show loading state
    const saveBtn = $('#create .btn-primary');
    const originalText = saveBtn.html();
    saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    
    $.ajax({
        url: "/user/role/store",
        type: "POST",
        data: {
            name: roleName,
            dash: permissions,
            _token: CsrfToken
        },
        success: function(response) {
            // Close modal
            $('#create').modal('hide');
            
            // Reset form
            $('#roleName').val('');
            $('#create .permission-checkbox').prop('checked', false);
            $('#create .module-checkbox').prop('checked', false);
            $('#checkAllPermissions').prop('checked', false);
            
            // Clear validation errors
            $('#nameError').text('');
            $('#permissionsError').text('');
            $('#roleName').removeClass('is-invalid');
            
            // Reload DataTable
            $('#details-datatable').DataTable().ajax.reload();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            let errorMessage = 'Something went wrong!';
            
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                // Handle validation errors
                const errors = xhr.responseJSON.errors;
                if (errors.name) {
                    $('#roleName').addClass('is-invalid');
                    $('#nameError').text(errors.name[0]);
                }
                if (errors.dash) {
                    $('#permissionsError').text(errors.dash[0]);
                }
                errorMessage = 'Please check the form for errors';
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
            // Restore button state
            saveBtn.prop('disabled', false).html(originalText);
        }
    });
}

// Edit Role function
function initializeEditModalCheckboxes() {
    // Check all permissions in edit modal
    $('#checkAllPermissionsEdit').off('change').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('#update .permission-checkbox-edit').prop('checked', isChecked);
        $('#update .module-checkbox-edit').prop('checked', isChecked);
    });
    
    // Module checkbox functionality for edit modal
    $('#update .module-checkbox-edit').off('change').on('change', function() {
        const module = $(this).data('module');
        const isChecked = $(this).prop('checked');
        $(`#update .isscheck_${module}.permission-checkbox-edit`).prop('checked', isChecked);
    });
    
    // Update module checkbox based on individual permissions
    $('#update .permission-checkbox-edit').off('change').on('change', function() {
        updateModuleCheckboxEdit(this);
    });
}

// Edit Role function - FIXED VERSION
function editRole(id) {
    $.ajax({
        url: "/user/role/edit",
        type: "POST",
        data: {
            id: id,
            _token: CsrfToken
        },
        // beforeSend: function() {
        //     // Show loading in modal
        //     $('#update .modal-body').html(`
        //         <div class="text-center p-5">
        //             <div class="spinner-border text-primary" role="status">
        //                 <span class="visually-hidden">Loading...</span>
        //             </div>
        //             <p class="mt-2">Loading role data...</p>
        //         </div>
        //     `);
        // },
        success: function(response) {
            // Restore modal content from hidden template
            const modalContent = $('#updateModalContent').html();
            $('#update .modal-body').html(modalContent);
            
            // Debug: Check response structure
            console.log('Edit Role Response:', response);
            
            // Populate form fields - FIXED: Access response.role
            $('#updateName').val(response.role.name);
            $('#editid').val(response.role.id);
            
            // Uncheck all permissions first
            $('#update .permission-checkbox-edit').prop('checked', false);
            
            // Check selected permissions
            if (response.permission && response.permission.length > 0) {
                response.permission.forEach(function(permissionId) {
                    // Debug: Check if checkbox exists
                    const checkbox = $(`#update #edit_permission_${permissionId}`);
                    if (checkbox.length > 0) {
                        checkbox.prop('checked', true);
                    } else {
                        console.warn(`Checkbox not found: edit_permission_${permissionId}`);
                    }
                });
            }
            
            // Update module checkboxes
            updateAllModuleCheckboxesEdit();
            
            // Initialize checkbox events
            initializeEditModalCheckboxes();
        },
        error: function(xhr) {
            console.error('Error loading role:', xhr);
            let errorMessage = 'Failed to load role data';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.status === 403) {
                errorMessage = 'You are not authorized to edit roles';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            }).then(() => {
                $('#update').modal('hide');
            });
        }
    });
}

// Update Role function - NEW FUNCTION
function updateRole() {
    // Validate form
    if (!validateEditForm()) {
        return;
    }
    
    const roleId = $('#editid').val();
    const roleName = $('#updateName').val().trim();
    const permissions = [];
    
    // Collect checked permission IDs
    $('#update .permission-checkbox-edit:checked').each(function() {
        permissions.push($(this).val());
    });
    
    // Show loading state
    const updateBtn = $('#update .btn-primary[type="button"]');
    const originalText = updateBtn.html();
    updateBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    
    $.ajax({
        url: "/user/role/update",
        type: "POST",
        data: {
            editid: roleId,
            name: roleName,
            permission: permissions,
            _token: CsrfToken
        },
        success: function(response) {
            // Close modal
            $('#update').modal('hide');
            
            // Clear validation errors
            $('#updateNameError').text('');
            $('#editPermissionsError').text('');
            $('#updateName').removeClass('is-invalid');
            
            // Reload DataTable
            $('#details-datatable').DataTable().ajax.reload();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.success,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    container: 'swal2-high-zindex'
                }
            });
        },
        error: function(xhr) {
            let errorMessage = 'Something went wrong!';
            
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                // Handle validation errors
                const errors = xhr.responseJSON.errors;
                if (errors.name) {
                    $('#updateName').addClass('is-invalid');
                    $('#updateNameError').text(errors.name[0]);
                }
                if (errors.permission) {
                    $('#editPermissionsError').text(errors.permission[0]);
                }
                errorMessage = 'Please check the form for errors';
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.status === 403) {
                errorMessage = 'You are not authorized to update roles';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                customClass: {
                    container: 'swal2-high-zindex'
                }
            });
        },
        complete: function() {
            // Restore button state
            updateBtn.prop('disabled', false).html(originalText);
        }
    });
}

// Delete Role function
function deleteRole(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This role will be deleted permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        customClass: {
            container: 'swal2-high-zindex'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/user/role/destroy",
                type: "POST",
                data: {
                    id: id,
                    _token: CsrfToken
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            container: 'swal2-high-zindex'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    $('#details-datatable').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            container: 'swal2-high-zindex'
                        }
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to delete role';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.status === 403) {
                        errorMessage = 'You are not authorized to delete roles';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        customClass: {
                            container: 'swal2-high-zindex'
                        }
                    });
                }
            });
        }
    });
}

// Helper function to update module checkbox in create modal
function updateModuleCheckbox(checkbox) {
    const $checkbox = $(checkbox);
    const classes = $checkbox.attr('class') || '';
    const moduleClass = classes.split(' ').find(cls => cls.startsWith('isscheck_'));
    
    if (moduleClass) {
        const module = moduleClass.replace('isscheck_', '');
        const allChecked = $(`#create .isscheck_${module}`).length === 
                          $(`#create .isscheck_${module}:checked`).length;
        $(`#create .module-checkbox[data-module="${module}"]`).prop('checked', allChecked);
        
        // Update "check all" checkbox
        const allPermissionsChecked = $('#create .permission-checkbox').length === 
                                     $('#create .permission-checkbox:checked').length;
        $('#checkAllPermissions').prop('checked', allPermissionsChecked);
    }
}

// Helper function to update module checkbox in edit modal
function updateModuleCheckboxEdit(checkbox) {
    const $checkbox = $(checkbox);
    const classes = $checkbox.attr('class') || '';
    const moduleClass = classes.split(' ').find(cls => cls.startsWith('isscheck_'));
    
    if (moduleClass) {
        const module = moduleClass.replace('isscheck_', '');
        const allChecked = $(`#update .isscheck_${module}.permission-checkbox-edit`).length === 
                          $(`#update .isscheck_${module}.permission-checkbox-edit:checked`).length;
        $(`#update .module-checkbox-edit[data-module="${module}"]`).prop('checked', allChecked);
        
        // Update "check all" checkbox
        const allPermissionsChecked = $('#update .permission-checkbox-edit').length === 
                                     $('#update .permission-checkbox-edit:checked').length;
        $('#checkAllPermissionsEdit').prop('checked', allPermissionsChecked);
    }
}

// Update all module checkboxes in edit modal
function updateAllModuleCheckboxesEdit() {
    $('#update .module-checkbox-edit').each(function() {
        const module = $(this).data('module');
        const allChecked = $(`#update .isscheck_${module}.permission-checkbox-edit`).length === 
                          $(`#update .isscheck_${module}.permission-checkbox-edit:checked`).length;
        $(this).prop('checked', allChecked);
    });
    
    // Update "check all" checkbox
    const allChecked = $('#update .permission-checkbox-edit').length === 
                      $('#update .permission-checkbox-edit:checked').length;
    $('#checkAllPermissionsEdit').prop('checked', allChecked);
}

// Form validation for create
function validateCreateForm() {
    let isValid = true;
    
    // Clear previous errors
    $('#nameError').text('');
    $('#permissionsError').text('');
    $('#roleName').removeClass('is-invalid');
    
    // Validate role name
    const roleName = $('#roleName').val().trim();
    if (!roleName) {
        $('#roleName').addClass('is-invalid');
        $('#nameError').text('Please enter role name');
        isValid = false;
    } else if (roleName.length < 3) {
        $('#roleName').addClass('is-invalid');
        $('#nameError').text('Role name must be at least 3 characters');
        isValid = false;
    }
    
    // Validate at least one permission selected
    const checkedPermissions = $('#create .permission-checkbox:checked').length;
    if (checkedPermissions === 0) {
        $('#permissionsError').text('Please select at least one permission');
        isValid = false;
    }
    
    return isValid;
}

// Form validation for edit
function validateEditForm() {
    let isValid = true;
    
    // Clear previous errors
    $('#updateNameError').text('');
    $('#editPermissionsError').text('');
    $('#updateName').removeClass('is-invalid');
    
    // Validate role name
    const roleName = $('#updateName').val().trim();
    if (!roleName) {
        $('#updateName').addClass('is-invalid');
        $('#updateNameError').text('Please enter role name');
        isValid = false;
    } else if (roleName.length < 3) {
        $('#updateName').addClass('is-invalid');
        $('#updateNameError').text('Role name must be at least 3 characters');
        isValid = false;
    }
    
    // Validate at least one permission selected
    const checkedPermissions = $('#update .permission-checkbox-edit:checked').length;
    if (checkedPermissions === 0) {
        $('#editPermissionsError').text('Please select at least one permission');
        isValid = false;
    }
    
    return isValid;
}