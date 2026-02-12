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
            <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                type="password" name="password" required autocomplete="current-password" />
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
</x-guest-layout>