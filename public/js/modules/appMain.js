import { handleAlert } from "../services/ui.js";
import { validatePanelForm } from "./panelValidators.js";

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".ajax-form").forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      if (!validatePanelForm(form)) return;

      const confirmMsg = form.dataset.confirm;
      if (confirmMsg) {
        const result = await Swal.fire({
          icon: "question",
          title: "Confirmar",
          text: confirmMsg,
          showCancelButton: true,
          confirmButtonText: "Sí",
          cancelButtonText: "Cancelar",
        });
        if (!result.isConfirmed) return;
      }

      const action = form.dataset.action || form.getAttribute("action") || window.location.pathname;
      const formData = new FormData(form);

      try {
        const response = await fetch(action, {
          method: "POST",
          body: formData,
        });
        const data = JSON.parse(await response.text());
        handleAlert(data.status, data.message, data.redirect);
      } catch (err) {
        console.error(err);
        handleAlert("error", "No se pudo procesar la solicitud.");
      }
    });
  });
});
