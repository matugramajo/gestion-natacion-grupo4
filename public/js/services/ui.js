/**
 * Servicio de Interfaz de Usuario
 * Centraliza el uso de SweetAlert2 para estandarizar las respuestas del sistema.
 * Permite manejar distintos tipos de alertas (success, error, warning, info)
 * y controlar comportamientos como redirección o modo silencioso.
 */

const SWAL_PRIMARY = "#1a6cf6";
const SWAL_CANCEL = "#9ca3af";

/** Instancia con botones celestes (evita el violeta por defecto de SweetAlert2). */
export const swal = Swal.mixin({
  confirmButtonColor: SWAL_PRIMARY,
  cancelButtonColor: SWAL_CANCEL,
});

export const handleAlert = (status, message, redirectUrl = null, options = {}) => {
  const { silent = false } = options;

  // Definimos el estado recibido desde el backend
  console.log("STATUS REAL:", status);

  // ERRORES CRÍTICOS
  // Se muestran siempre, sin excepción
  if (status === "error") {
    return swal.fire({
      icon: "error",
      title: "Error",
      text: message,
    });
  }

  // ADVERTENCIAS DE VALIDACIÓN
  if (status === "warning") {
    return swal.fire({
      icon: "warning",
      title: "Atención",
      text: message,
    });
  }

  // MENSAJES INFORMATIVOS
  if (status === "info" || status === "user_exists") {
    return swal.fire({
      icon: "info",
      title: "Aviso",
      text: message,
      confirmButtonText: "Aceptar",
    }).then((result) => {
      if (result.isConfirmed && redirectUrl) {
        window.location.href = redirectUrl;
      }
    });
  }

  // OPERACIONES EXITOSAS
  if (status === "success") {

    // LOGIN: modo silencioso (no mostrar popup)
    if (silent) {
      if (redirectUrl) {
        window.location.href = redirectUrl;
      }
      return;
    }

    // OTROS CASOS: mostrar popup
    return swal.fire({
      icon: "success",
      title: "Listo",
      text: message,
    }).then(() => {
      if (redirectUrl) {
        window.location.href = redirectUrl;
      }
    });
  }

  // CASO POR DEFECTO
  return swal.fire({
    icon: "info",
    title: "Aviso",
    text: message,
  });
};