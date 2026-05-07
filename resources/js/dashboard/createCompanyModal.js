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
        submitBtn.textContent = 'Cargando';
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
                credentials: 'same-origin', // Important for CSRF
                redirect: 'follow', // Follow redirects automatically
            });

            // Handle redirect responses
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            // Handle successful responses
            if (response.ok) {
                const data = await response.json().catch(() => ({}));

                // Check if response contains a redirect URL
                if (data?.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                // Otherwise reload the page
                window.location.reload();
                return;
            }

            // Handle error responses
            if (response.status === 422) {
                // Validation errors
                const data = await response.json().catch(() => ({}));
                const message = data?.message || 'Error al validar los datos. Inténtalo de nuevo.';

                if (errorContainer) {
                    errorContainer.textContent = message;
                    errorContainer.classList.remove('hidden');
                }
            } else if (response.status >= 400) {
                // Other HTTP errors
                const data = await response.json().catch(() => ({}));
                const message =
                    data?.message || `Error ${response.status}: No se pudo crear la empresa.`;

                if (errorContainer) {
                    errorContainer.textContent = message;
                    errorContainer.classList.remove('hidden');
                }

                console.error('Company creation error:', response.status, data);
            }
        } catch (error) {
            console.error('Network or fetch error:', error);

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
