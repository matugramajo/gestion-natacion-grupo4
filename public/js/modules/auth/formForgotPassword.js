/**
 * Maneja el envío del formulario de recuperación de contraseña.
 * Se comunica con el endpoint 'send-reset' definido en el router.php.
 */
import { handleAlert } from "../../services/ui.js";

export function initForgotPassword() {
    const form = document.getElementById("formForgotPassword");

    // Si el formulario no existe en la vista actual, salimos para evitar errores
    if (!form) return;

    // Capturamos el botón para poder bloquearlo mientras se procesa la solicitud
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Evitamos que se envíe más de una vez
        if (submitBtn.disabled) return;

        // Empaquetamos los datos del formulario (el email en este caso)
        const formData = new FormData(form);

        // Bloqueamos el botón mientras se procesa la solicitud
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Enviando...
        `;

        try {
            /**
             * Enviamos la petición asíncrona.
             * El 'await' detiene la ejecución hasta que el servidor responda,
             * permitiendo un código más limpio y legible (sin .then).
             */
            const response = await fetch("?url=send-reset", {
                method: "POST",
                body: formData,
            });

            // Convertimos la respuesta cruda del servidor a un objeto JSON
            const data = await response.json();

            // Delegamos la respuesta visual al servicio central de alertas
            handleAlert(data.status, data.message, data.redirect);

        } catch (error) {
            console.error("Recovery Error:", error);
            handleAlert("error", "No se pudo procesar la solicitud de recuperación.");
        } finally {
            /**
             * Independientemente del resultado (éxito o error),
             * restauramos el botón a su estado original.
             */
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Enviar enlace de recuperación";
        }
    });
}