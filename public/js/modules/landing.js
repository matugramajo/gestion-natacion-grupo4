import { swal } from "../services/ui.js";

/**
 * Comportamiento de la landing pública (sin layout auth).
 */
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  if (params.get("logout") !== "1") return;

  swal.fire({
    icon: "success",
    title: "Sesión cerrada",
    text: "Cerraste sesión correctamente. ¡Hasta pronto!",
    showConfirmButton: false,
    timer: 2000,
  });

  params.delete("logout");
  const qs = params.toString();
  history.replaceState(
    {},
    "",
    window.location.pathname + (qs ? `?${qs}` : "")
  );
});
