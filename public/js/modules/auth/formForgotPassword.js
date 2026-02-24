import { handleAlert } from "../../services/ui.js";

export function initForgotPassword() {
  const form = document.getElementById("forgotForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("?url=send-reset", {
        method: "POST",
        body: formData,
      });
      const data = await response.json();
      handleAlert(data.status, data.message, data.redirect);
    } catch (error) {
      handleAlert("error", "No se pudo procesar la solicitud de recuperación.");
    }
  });
}
