/**
 * CreateCompanyModal - Modal de creación de empresa.
 * Muestra un modal para que el usuario sin tenant cree su empresa.
 * Envía el formulario al endpoint POST /company y redirige al dashboard.
 */

const modal = document.getElementById('create-company-modal');
const form = document.getElementById('create-company-form');
const submitBtn = document.getElementById('create-company-submit');
const errorContainer = document.getElementById('create-company-error');

if (form) {
    form.addEventListener('submit', async e => {
        e.preventDefault();

        // Mostrar estado de carga
        submitBtn.disabled = true;
        submitBtn.textContent = '⌛ Creando...';
        errorContainer?.classList.add('hidden');

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: formData,
            });

            if (response.redirected) {
                // Redirección exitosa — seguir la redirección
                window.location.href = response.url;
                return;
            }

            if (response.ok) {
                window.location.reload();
                return;
            }

            // Manejar errores de validación (422)
            const data = await response.json().catch(() => null);
            const message = data?.message || 'Error al crear la empresa. Inténtalo de nuevo.';

            if (errorContainer) {
                errorContainer.textContent = message;
                errorContainer.classList.remove('hidden');
            }
        } catch {
            if (errorContainer) {
                errorContainer.textContent = 'Error de conexión. Inténtalo de nuevo.';
                errorContainer.classList.remove('hidden');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Crear Empresa';
        }
    });
}
