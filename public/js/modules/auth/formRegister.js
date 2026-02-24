import { handleAlert } from "../../services/ui.js";

export function initRegister() {
  const form = document.getElementById("registerForm");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch("?url=register", {
        method: "POST",
        body: formData,
      });

      const text = await response.text();

      try {
        const data = JSON.parse(text);
        // Maneja el éxito, el error de validación o el 'user_exists'
        handleAlert(data.status, data.message, data.redirect);
      } catch (err) {
        console.error("Respuesta no JSON:", text);
        handleAlert("error", "Error crítico en el registro.");
      }
    } catch (error) {
      handleAlert("error", "Error de red al intentar registrarse.");
    }
  });
}
