{{-- Modal de creación de empresa. Solo se muestra si $showModal es true (usuario sin tenant). --}}
<div id="create-company-modal">
    {{-- Overlay de fondo --}}
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:40;"></div>

    {{-- Modal --}}
    <div style="position:fixed; inset:0; display:flex; align-items:center; justify-content:center; z-index:50; padding:16px;">
        <div style="background:#fff; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.15); width:100%; max-width:440px; padding:32px;">

            <div style="margin-bottom:24px;">
                <h2 style="font-size:22px; font-weight:700; color:#1c1c1e; margin:0 0 6px;">Crear tu Empresa</h2>
                <p style="font-size:14px; color:#8e8e93; margin:0;">Completa la información básica de tu empresa para comenzar.</p>
            </div>

            <form id="create-company-form"
                  action="{{ route('company.store') }}"
                  method="POST"
                  style="display:flex; flex-direction:column; gap:16px;">
                @csrf

                {{-- Nombre --}}
                <div>
                    <label for="company_name"
                           style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
                        Nombre de la Empresa
                    </label>
                    <input type="text" id="company_name" name="company_name"
                           placeholder="ej: Mi Empresa SL"
                           required
                           style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
                </div>

                {{-- Industria --}}
                <div>
                    <label for="industry"
                           style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
                        Industria
                    </label>
                    <input type="text" id="industry" name="industry"
                           placeholder="ej: Tecnología, Retail, Servicios"
                           required
                           style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
                </div>

                {{-- País --}}
                <div>
                    <label for="country"
                           style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
                        País
                    </label>
                    <input type="text" id="country" name="country"
                           placeholder="ej: España, México, Colombia"
                           required
                           style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
                </div>

                {{-- Mensaje de error --}}
                <div id="create-company-error"
                     style="background:#fff5f5; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; font-size:13px; color:#dc2626; display:none;">
                </div>

                {{-- Submit --}}
                <button type="submit" id="create-company-submit"
                        style="width:100%; background:#007aff; color:#fff; padding:12px; border:none; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; margin-top:4px;">
                    Crear Empresa
                </button>
            </form>

            <p style="font-size:12px; color:#8e8e93; text-align:center; margin-top:16px; margin-bottom:0;">
                Puedes editar esta información más tarde en los ajustes de la empresa.
            </p>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/dashboard/createCompanyModal.js')
@endpush
