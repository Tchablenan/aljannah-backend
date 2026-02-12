<x-guest-layout>
    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" method="POST"
        action="{{ route('password.email') }}">
        @csrf

        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">Mot de passe oublié ?</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">Entrez votre email pour réinitialiser votre mot de passe.</div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-gray-900 fs-6">Email</label>
            <input class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="submit" id="kt_password_reset_submit" class="btn btn-lg btn-primary fw-bolder me-4">
                <span class="indicator-label">Envoyer le lien</span>
            </button>
            <a href="{{ route('login') }}" class="btn btn-lg btn-light-primary fw-bolder">Annuler</a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</x-guest-layout>