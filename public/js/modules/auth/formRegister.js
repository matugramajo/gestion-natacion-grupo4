/**
 * Gestión del alta de alumnos mediante AJAX.
 * Este módulo captura los datos del formulario, valida archivos en el cliente
 * y los envía al controlador mediante la API Fetch.
 */
import { handleAlert } from "../../services/ui.js";

export function initRegister() {
  const form = document.getElementById("formRegister");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

  const password = form.querySelector('input[name="password"]').value;
  const confirmPassword = form.querySelector('input[name="confirm_password"]').value;

  if ( password !== confirmPassword ) {
      return handleAlert( 'warning', 'Las contraseñas no coinciden.' );
  }
    /**
     * Capturamos el archivo de imagen para validarlo antes de enviarlo.
     * Es una buena práctica para ahorrar ancho de banda y no saturar el servidor
     * con archivos que no cumplen los requisitos.
     */
    const fileInput = form.querySelector('input[name="profile_image"]');
    const file = fileInput ? fileInput.files[0] : null;

    if (file) {
      // Validamos que el formato sea exclusivamente de imagen
      const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
      if (!allowedTypes.includes(file.type)) {
        return handleAlert(
          "error",
          "Formato no válido. Solo se permiten imágenes JPG, PNG o GIF.",
        );
      }

      // Validamos que el tamaño no exceda los 2MB para optimizar el almacenamiento
      const maxSize = 2 * 1024 * 1024;
      if (file.size > maxSize) {
        return handleAlert(
          "warning",
          "La imagen es muy pesada. El límite es de 2MB.",
        );
      }
    }

    /**
     * FormData empaqueta automáticamente todos los campos del formulario,
     * incluyendo los archivos (files), siempre que el input tenga el atributo 'name'.
     */
    const formData = new FormData(form);

    try {
      const response = await fetch("?url=register", {
        method: "POST",
        body: formData,
      });

      const text = await response.text();
      try {
        const data = JSON.parse(text);
        // El servidor retornará el status (success, error, warning) y el mensaje
        handleAlert(data.status, data.message, data.redirect);
      } catch (err) {
        // En caso de un error crítico de PHP (Fatal Error), la respuesta no será un JSON válido
        console.error("Respuesta inesperada del servidor:", text);
        handleAlert(
          "error",
          "Error crítico en el servidor. Revisar consola de red.",
        );
      }
    } catch (error) {
      console.error("Error en la conexión Fetch:", error);
      handleAlert("error", "No se pudo establecer conexión con el servidor.");
    }
  });
}
