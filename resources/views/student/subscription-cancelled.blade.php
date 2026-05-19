@extends('layouts.admin.app')

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            html: `You've been unsubscribed from our Portal. You will no longer receive updates & offers. <br>
                   If this was a mistake or you change your mind, you can resubscribe on click below.`,
            imageUrl: "https://escienceacademy.com/build/assets/frontend/images/escience-logo.svg",
            imageHeight: 100,
            confirmButtonColor: "#3A833A",
            confirmButtonText: "RESUBSCRIBE",
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
               window.location.href = "{{ route('ReNewPackage') }}";
            }
        });
    });
</script>
@endpush