// Global variable for CSRF token
const CsrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();
    
    // Handle create form submission
    $('#createUserForm').submit(function(e) {
        e.preventDefault();
        createUser();
    });
    
    // Handle update form submission
    $('#updateUserForm').submit(function(e) {
        e.preventDefault();
        updateUser();
    });
    
    // Initialize Select2
    if ($.fn.select2) {
        $('.select2-show-search').select2({
            dropdownParent: $('#create, #update')
        });
    }
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
            url: "/user/show",
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
                        text: 'You are not authorized to view users'
                    });
                }
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '50px'
            },
            { 
                data: 'name', 
                name: 'name',
                width: '150px'
            },
            { 
                data: 'email', 
                name: 'email',
                width: '200px'
            },
            { 
                data: 'role', 
                name: 'role',
                width: '100px'
            },
            { 
                data: 'status', 
                name: 'status',
                orderable: false,
                searchable: false,
                width: '80px'
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
            emptyTable: "No users found",
        }
    });
}

// Create User function
function createUser() {
    // Validate form
    if (!validateCreateForm()) {
        return;
    }
    
    const formData = {
        name: $('#name').val().trim(),
        email: $('#email').val().trim(),
        password: $('#password').val(),
        role: $('#role').val(),
        status: $('#status').val(),
        _token: CsrfToken
    };
    
    // Show loading state
    const saveBtn = $('#create .btn-primary');
    const originalText = saveBtn.html();
    saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    
    $.ajax({
        url: "/user/store",
        type: "POST",
        data: formData,
        success: function(response) {
            // Close modal
            $('#create').modal('hide');
            
            // Reset form
            $('#createUserForm')[0].reset();
            clearValidationErrors('create');
            
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
                displayValidationErrors(errors, 'create');
                errorMessage = 'Please check the form for errors';
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.status === 403) {
                errorMessage = 'You are not authorized to create users';
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

// Edit User function
function editUser(id) {
    $.ajax({
        url: "/user/edit",
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
        //             <p class="mt-2">Loading user data...</p>
        //         </div>
        //     `);
        // },
        success: function(response) {
            // Populate form fields
            $('#updateId').val(response.user.id);
            $('#updateName').val(response.user.name);
            $('#updateEmail').val(response.user.email);
            $('#updateRole').val(response.role_id).trigger('change');
            $('#updateStatus').val(response.user.status).trigger('change');
            
            // Clear validation errors
            clearValidationErrors('update');
        },
        error: function(xhr) {
            console.error('Error loading user:', xhr);
            let errorMessage = 'Failed to load user data';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.status === 403) {
                errorMessage = 'You are not authorized to edit users';
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

// Update User function
function updateUser() {
    // Validate form
    if (!validateUpdateForm()) {
        return;
    }
    
    const formData = {
        updateId: $('#updateId').val(),
        updateName: $('#updateName').val().trim(),
        updateEmail: $('#updateEmail').val().trim(),
        updateRole: $('#updateRole').val(),
        updateStatus: $('#updateStatus').val(),
        _token: CsrfToken
    };
    
    // Show loading state
    const updateBtn = $('#update .btn-primary');
    const originalText = updateBtn.html();
    updateBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
    
    $.ajax({
        url: "/user/update",
        type: "POST",
        data: formData,
        success: function(response) {
            // Close modal
            $('#update').modal('hide');
            
            // Clear validation errors
            clearValidationErrors('update');
            
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
                displayValidationErrors(errors, 'update');
                errorMessage = 'Please check the form for errors';
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.status === 403) {
                errorMessage = 'You are not authorized to update users';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        },
        complete: function() {
            // Restore button state
            updateBtn.prop('disabled', false).html(originalText);
        }
    });
}

// Delete User function
function userDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This user will be deleted permanently!",
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
                url: "/user/destroy",
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
                    let errorMessage = 'Failed to delete user';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.status === 403) {
                        errorMessage = 'You are not authorized to delete users';
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

// Form validation for create
function validateCreateForm() {
    let isValid = true;
    
    // Clear previous errors
    clearValidationErrors('create');
    
    // Validate name
    const name = $('#name').val().trim();
    if (!name) {
        $('#name').addClass('is-invalid');
        $('#nameError').text('Please enter name');
        isValid = false;
    }
    
    // Validate email
    const email = $('#email').val().trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        $('#email').addClass('is-invalid');
        $('#emailError').text('Please enter email');
        isValid = false;
    } else if (!emailRegex.test(email)) {
        $('#email').addClass('is-invalid');
        $('#emailError').text('Please enter a valid email address');
        isValid = false;
    }
    
    // Validate password
    const password = $('#password').val();
    if (!password) {
        $('#password').addClass('is-invalid');
        $('#passwordError').text('Please enter password');
        isValid = false;
    } else if (password.length < 8) {
        $('#password').addClass('is-invalid');
        $('#passwordError').text('Password must be at least 8 characters');
        isValid = false;
    }
    
    // Validate role
    const role = $('#role').val();
    if (!role) {
        $('#role').addClass('is-invalid');
        $('#roleError').text('Please select a role');
        isValid = false;
    }
    
    return isValid;
}

// Form validation for update
function validateUpdateForm() {
    let isValid = true;
    
    // Clear previous errors
    clearValidationErrors('update');
    
    // Validate name
    const name = $('#updateName').val().trim();
    if (!name) {
        $('#updateName').addClass('is-invalid');
        $('#updateNameError').text('Please enter name');
        isValid = false;
    }
    
    // Validate email
    const email = $('#updateEmail').val().trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        $('#updateEmail').addClass('is-invalid');
        $('#updateEmailError').text('Please enter email');
        isValid = false;
    } else if (!emailRegex.test(email)) {
        $('#updateEmail').addClass('is-invalid');
        $('#updateEmailError').text('Please enter a valid email address');
        isValid = false;
    }
    
    // Validate role
    const role = $('#updateRole').val();
    if (!role) {
        $('#updateRole').addClass('is-invalid');
        $('#updateRoleError').text('Please select a role');
        isValid = false;
    }
    
    return isValid;
}

// Helper function to clear validation errors
function clearValidationErrors(formType) {
    const prefix = formType === 'create' ? '' : 'update';
    
    $(`#${prefix}name`).removeClass('is-invalid');
    $(`#${prefix}email`).removeClass('is-invalid');
    $(`#${prefix}role`).removeClass('is-invalid');
    $(`#${prefix}status`).removeClass('is-invalid');
    
    if (formType === 'create') {
        $('#password').removeClass('is-invalid');
        $('#passwordError').text('');
    }
    
    $(`#${prefix}nameError`).text('');
    $(`#${prefix}emailError`).text('');
    $(`#${prefix}roleError`).text('');
    $(`#${prefix}statusError`).text('');
}

// Helper function to display validation errors
function displayValidationErrors(errors, formType) {
    const prefix = formType === 'create' ? '' : 'update';
    
    // Clear all errors first
    clearValidationErrors(formType);
    
    // Display new errors
    if (errors.name) {
        $(`#${prefix}name`).addClass('is-invalid');
        $(`#${prefix}nameError`).text(errors.name[0]);
    }
    
    if (errors.email) {
        $(`#${prefix}email`).addClass('is-invalid');
        $(`#${prefix}emailError`).text(errors.email[0]);
    }
    
    if (errors.role) {
        $(`#${prefix}role`).addClass('is-invalid');
        $(`#${prefix}roleError`).text(errors.role[0]);
    }
    
    if (errors.status) {
        $(`#${prefix}status`).addClass('is-invalid');
        $(`#${prefix}statusError`).text(errors.status[0]);
    }
    
    if (formType === 'create' && errors.password) {
        $('#password').addClass('is-invalid');
        $('#passwordError').text(errors.password[0]);
    }
}