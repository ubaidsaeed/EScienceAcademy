@extends('layouts.admin.app')

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Package Expired',
            text: "{{ session('expirePackage') }}",
            confirmButtonText: 'Upgrade Now',
            confirmButtonColor: '#3A833A',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then(() => {
            // Redirect to subscription/upgrade page
             window.location.href = "{{ route('ReNewPackage') }}";
        });
    });
</script>
@endpush