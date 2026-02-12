<x-guest-layout>
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST"
        action="{{ route('register') }}">
        @csrf

        <!--begin::Heading-->
        <div class="mb-10 text-center">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">Créer un compte</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">Vous avez déjà un compte ?
                <a href="{{ route('login') }}" class="link-primary fw-bolder">Connectez-vous ici</a>
            </div>
            <!--end::Link-->
        </div>
        <!--end::Heading-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Nom</label>
            <input class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror"
                type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6">Email</label>
            <input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10 fv-row" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6">Mot de passe</label>
                <!--end::Label-->
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input
                        class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                        type="password" name="password" required autocomplete="new-password" />
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>
                <!--end::Input wrapper-->
            </div>
            <!--end::Wrapper-->
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-dark fs-6">Confirmer le mot de passe</label>
            <input class="form-control form-control-lg form-control-solid" type="password" name="password_confirmation"
                required autocomplete="new-password" />
        </div>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="text-center">
            <button type="submit" id="kt_sign_up_submit" class="btn btn-lg btn-primary w-100">
                <span class="indicator-label">S'inscrire</span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</x-guest-layout>