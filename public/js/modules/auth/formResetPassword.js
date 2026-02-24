import { handleAlert } from '../../services/ui.js';

export function initResetPassword() {
    const form = document.getElementById('resetPasswordForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const response = await fetch('?url=update-password', { method: 'POST', body: formData });
            const data = await response.json();
            handleAlert(data.status, data.message, data.redirect);
        } catch (error) {
            handleAlert('error', 'Error al intentar actualizar la contraseña.');
        }
    });
}