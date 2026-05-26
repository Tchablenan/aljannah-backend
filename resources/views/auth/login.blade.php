<x-guest-layout>
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
        @csrf

        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">Connexion Aljannah</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">Nouveau ici ?
                <a href="{{ route('register') }}" class="link-primary fw-bolder">Créer un compte</a>
            </div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <!--end::Input-->
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack mb-2">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6 mb-0">Mot de passe</label>
                <!--end::Label-->
                <!--begin::Link-->
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">Mot de passe oublié ?</a>
                @endif
                <!--end::Link-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Input-->
            <div class="position-relative">
                <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                    type="password" name="password" id="password_input" required autocomplete="current-password" style="padding-right: 45px;" />
                <button type="button" id="toggle_password_btn" class="btn btn-icon position-absolute translate-middle-y top-50 end-0 me-3" style="border: none; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <!-- Eye SVG Icon -->
                    <svg id="eye_open_icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Slash SVG Icon (Hidden by default) -->
                    <svg id="eye_closed_icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-gray-500 d-none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858-.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <!--end::Input-->
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Remember Me-->
        <div class="fv-row mb-10">
            <label class="form-check form-check-custom form-check-solid form-check-inline">
                <input class="form-check-input" type="checkbox" name="remember" />
                <span class="form-check-label fw-bold text-gray-700 fs-6">Se souvenir de moi</span>
            </label>
        </div>
        <!--end::Remember Me-->

        <!--begin::Actions-->
        <div class="text-center">
            <!--begin::Submit button-->
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">Se connecter</span>
            </button>
            <!--end::Submit button-->
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->

    <script>
        document.getElementById('toggle_password_btn').addEventListener('click', function () {
            const passwordInput = document.getElementById('password_input');
            const eyeOpenIcon = document.getElementById('eye_open_icon');
            const eyeClosedIcon = document.getElementById('eye_closed_icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpenIcon.classList.add('d-none');
                eyeClosedIcon.classList.remove('d-none');
            } else {
                passwordInput.type = 'password';
                eyeOpenIcon.classList.remove('d-none');
                eyeClosedIcon.classList.add('d-none');
            }
        });
    </script>
</x-guest-layout>