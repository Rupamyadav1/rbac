<x-guest-layout>
    <form id="registerForm">
        @csrf

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                required
            />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                required
            />
        </div>

        <div class="mt-4 hidden" id="otpBox">
            <x-input-label for="otp" value="OTP" />
            <x-text-input
                id="otp"
                class="block mt-1 w-full"
                type="text"
                name="otp"
            />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button type="submit" id="submitBtn">
                Register
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const otpBox = document.getElementById('otpBox');
    const btn = document.getElementById('submitBtn');
    const formData = new FormData(this);

    btn.disabled = true;

    if (otpBox.classList.contains('hidden')) {

        fetch("{{ route('register.sendOtp') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            if (res.status) {
                Swal.fire('OTP Sent', 'Check your email', 'success');
                otpBox.classList.remove('hidden');
                btn.innerText = 'Verify OTP';
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });

    }
    else {

        fetch("{{ route('register.verifyOtp') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;

            if (res.status) {
                Swal.fire('Success', 'Registration completed', 'success')
                    .then(() => {
                        window.location.href = res.redirect;
                    });
            } else {
                Swal.fire('Invalid OTP', res.message, 'error');
            }
        });
    }
});
</script>
