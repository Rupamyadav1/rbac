<x-guest-layout>
    <form id="loginForm">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
        </div>

        <div class="mt-4 hidden" id="otpBox">
            <x-input-label for="otp" value="OTP" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button id="loginBtn">
                Login
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const otpBox = document.getElementById('otpBox');
    const btn = document.getElementById('loginBtn');
    const formData = new FormData(this);

    btn.disabled = true;

    if (otpBox.classList.contains('hidden')) {

        fetch("{{ route('admin.login.sendOtp') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
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

    } else {

        fetch("{{ route('admin.login.verifyOtp') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                Swal.fire('Success', 'Logged in', 'success')
                .then(() => {
                    window.location.href = res.redirect;
                });
            } else {
                btn.disabled = false;
                Swal.fire('Invalid OTP', res.message, 'error');
            }
        });
    }
});
</script>

