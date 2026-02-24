import { handleAlert } from "../../services/ui.js";

export function initLogin() {
  const form = document.getElementById("loginForm");

  // Si el formulario no existe en la página actual, salimos para evitar errores
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch("?url=auth", {
        method: "POST",
        body: formData,
      });

      // Usamos .text() primero por si hay errores de PHP (como hicimos antes)
      const text = await response.text();

      try {
        const data = JSON.parse(text);
        // Si el login es OK, data.redirect enviará al alumno a su panel
        handleAlert(data.status, data.message, data.redirect);
      } catch (err) {
        console.error("Respuesta no JSON:", text);
        handleAlert("error", "Error en la respuesta del servidor.");
      }
    } catch (error) {
      handleAlert(
        "error",
        "No se pudo conectar con el servidor de autenticación.",
      );
    }
  });
}
