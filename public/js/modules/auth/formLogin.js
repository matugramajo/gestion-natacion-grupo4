/**
 * Gestiona el inicio de sesión mediante AJAX.
 * Implementa una técnica de captura de errores robusta para detectar fallos en PHP.
 */
import { handleAlert } from "../../services/ui.js";

export function initLogin() {
    const form = document.getElementById("formLogin");

    // "Early return" para evitar errores si el script se carga en otra página
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            // Apuntamos a 'authenticate', que es el nombre que definimos en el Router
            const response = await fetch("?url=authenticate", {
                method: "POST",
                body: formData,
            });

            /**
             * Obtenemos la respuesta como texto plano primero.
             * Esto es una red de seguridad: si PHP envía un error de sistema (HTML),
             * el JSON.parse fallaría, pero aquí podremos ver qué pasó exactamente.
             */
            const text = await response.text();

            try {
                const data = JSON.parse(text);
                
                // Si las credenciales son válidas, handleAlert procesará la redirección
                handleAlert(data.status, data.message, data.redirect);

            } catch (err) {
                // Si llegamos aquí, PHP devolvió algo que NO es JSON (posible error de sintaxis)
                console.error("Server response was not JSON:", text);
                handleAlert("error", "The server returned an invalid response. Check the console.");
            }

        } catch (error) {
            console.error("Connection Error:", error);
            handleAlert("error", "Could not connect to the authentication server.");
        }
    });
}