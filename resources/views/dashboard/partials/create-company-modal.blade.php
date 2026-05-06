{{-- Modal de creación de empresa. Solo se muestra si $showModal es true (usuario sin tenant). --}}
@vite(['resources/css/dashboard/features/control-panel/create-company-modal.css'])

<div id="create-company-modal">
    <div class="create-company-modal__overlay"></div>

    <div class="create-company-modal__dialog">
        <div class="create-company-modal__card">

            <div class="create-company-modal__header">
                <h2 class="create-company-modal__title">Crear tu Empresa</h2>
                <p class="create-company-modal__subtitle">Completa la información básica de tu empresa para comenzar.</p>
            </div>

            <form id="create-company-form"
                  action="{{ route('company.store') }}"
                  method="POST"
                  class="create-company-modal__form">
                @csrf

                <div class="create-company-modal__field">
                    <label for="company_name">Nombre de la Empresa</label>
                    <input type="text" id="company_name" name="company_name"
                           placeholder="ej: Mi Empresa SL"
                           required
                           class="create-company-modal__input">
                </div>

                <div class="create-company-modal__field">
                    <label for="industry">Industria</label>
                    <input type="text" id="industry" name="industry"
                           placeholder="ej: Tecnología, Retail, Servicios"
                           required
                           class="create-company-modal__input">
                </div>

                <div class="create-company-modal__field">
                    <label for="country">País</label>
                    <input type="text" id="country" name="country"
                           placeholder="ej: España, México, Colombia"
                           required
                           class="create-company-modal__input">
                </div>

                <div id="create-company-error" class="create-company-modal__error"></div>

                <button type="submit" id="create-company-submit" class="create-company-modal__submit">
                    Crear Empresa
                </button>
            </form>

            <p class="create-company-modal__footer">
                Puedes editar esta información más tarde en los ajustes de la empresa.
            </p>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/dashboard/createCompanyModal.js')
@endpush
