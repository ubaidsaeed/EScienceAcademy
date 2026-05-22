
document.addEventListener('livewire:load', function () {
    // Set timeout to hide success and error alerts after 3 seconds
    const alertMessage = document.querySelector('.alert-message');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.display = 'none';
            alertMessage.classList.remove('show');
            alertMessage.classList.add('fade');
        }, 3000); // 3000 milliseconds = 3 seconds
    }
});
document.addEventListener('alertSuccess', function (event) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: event.detail
    });
});
document.addEventListener('alertError', function (event) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "error",
        title: event.detail
    });
});


