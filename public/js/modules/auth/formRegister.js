/**
 * Gestiona el alta de nuevos alumnos mediante AJAX.
 * Este módulo se comunica con la lógica de transacciones del UserController.
 */
import { handleAlert } from "../../services/ui.js";

export function initRegister() {
  const form = document.getElementById("formRegister");
  console.log("Formulario de registro detectado");
  // Si no estamos en la vista de registro, abortamos la ejecución
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("Submit interceptado"); // <-- Y este otro
    /**
     * FormData es ideal para los alumnos: captura todos los campos del
     * formulario basándose en el atributo 'name' de los inputs.
     */
    const formData = new FormData(form);

    try {
      const response = await fetch("?url=register", {
        method: "POST",
        body: formData,
      });

      // Obtenemos la respuesta como texto para depuración (debug)
      const text = await response.text();

      try {
        const data = JSON.parse(text);

        /**
         * El servidor puede responder:
         * - 'success': Todo OK, redirige al login.
         * - 'warning': Faltan datos o pass muy corta.
         * - 'user_exists': El email ya está en la base de datos.
         */
        handleAlert(data.status, data.message, data.redirect);
      } catch (err) {
        // Si PHP falla (ej: error de SQL), lo veremos aquí
        console.error("Non-JSON response during registration:", text);
        handleAlert(
          "error",
          "The registration process encountered a server-side error.",
        );
      }
    } catch (error) {
      console.error("Network Error:", error);
      handleAlert("error", "Could not connect to the server for registration.");
    }
  });
}
