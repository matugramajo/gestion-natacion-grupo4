/**
 * Servicio de Interfaz de Usuario
 * Centraliza el uso de SweetAlert2 para estandarizar las respuestas del sistema.
 * Permite manejar distintos tipos de alertas (success, error, warning, info)
 * y controlar comportamientos como redirección o modo silencioso.
 */

export const handleAlert = (status, message, redirectUrl = null, options = {}) => {
  const { silent = false } = options;

  // Definimos el estado recibido desde el backend
  console.log("STATUS REAL:", status);

  // ERRORES CRÍTICOS
  // Se muestran siempre, sin excepción
  if (status === "error") {
    return Swal.fire({
      icon: "error",
      title: "Error",
      text: message,
    });
  }

  // ADVERTENCIAS DE VALIDACIÓN
  if (status === "warning") {
    return Swal.fire({
      icon: "warning",
      title: "Atención",
      text: message,
    });
  }

  // MENSAJES INFORMATIVOS
  if (status === "info" || status === "user_exists") {
    return Swal.fire({
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
    return Swal.fire({
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
  return Swal.fire({
    icon: "info",
    title: "Aviso",
    text: message,
  });
};