import { handleAlert } from "../../services/ui.js";

export function initCreateCoach() {
    const form = document.getElementById("formCreateCoach");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch("?url=coaches/store", {
                method: "POST",
                body: formData,
            });

            const text = await response.text();
            try {
                const data = JSON.parse(text);
                if ( data.status === 'success' ) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    handleAlert(data.status, data.message, data.redirect);
                }
            } catch (err) {
                console.error("Respuesta inesperada:", text);
                handleAlert("error", "Error crítico en el servidor.");
            }
        } catch (error) {
            console.error("Error en fetch:", error);
            handleAlert("error", "No se pudo conectar con el servidor.");
        }
    });
}
