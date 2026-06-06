import { handleAlert } from "../services/ui.js";

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function parseTime(value) {
  const [h, m] = value.split(":").map(Number);
  return h * 60 + m;
}

export function validatePanelForm(form) {
  const type = form.dataset.validate;
  if (!type) return true;

  if (type === "coach") {
    const first = form.querySelector('[name="first_name"]')?.value.trim();
    const last = form.querySelector('[name="last_name"]')?.value.trim();
    const email = form.querySelector('[name="email"]')?.value.trim();
    if (!first || !last || !email) {
      handleAlert("warning", "Completá nombre, apellido y email.");
      return false;
    }
    if (!isValidEmail(email)) {
      handleAlert("error", "El email no es válido.");
      return false;
    }
    return true;
  }

  if (type === "lesson") {
    const coach = form.querySelector('[name="coach_id"]')?.value;
    const level = form.querySelector('[name="level_id"]')?.value;
    const day = form.querySelector('[name="day_of_week"]')?.value;
    const start = form.querySelector('[name="start_time"]')?.value;
    const end = form.querySelector('[name="end_time"]')?.value;
    const capacity = parseInt(form.querySelector('[name="capacity"]')?.value, 10);

    if (!coach || !level || !day || !start || !end || !capacity) {
      handleAlert("warning", "Completá todos los campos de la clase.");
      return false;
    }
    if (capacity < 1) {
      handleAlert("warning", "Los cupos deben ser al menos 1.");
      return false;
    }
    if (parseTime(end) <= parseTime(start)) {
      handleAlert("warning", "La hora de fin debe ser posterior al inicio.");
      return false;
    }
    return true;
  }

  if (type === "password") {
    const current = form.querySelector('[name="current_password"]')?.value ?? "";
    const password = form.querySelector('[name="password"]')?.value ?? "";
    const confirm = form.querySelector('[name="confirm_password"]')?.value ?? "";

    if (!current || !password || !confirm) {
      handleAlert("warning", "Completá todos los campos de contraseña.");
      return false;
    }
    if (password.length < 6) {
      handleAlert("warning", "La contraseña debe tener al menos 6 caracteres.");
      return false;
    }
    if (password !== confirm) {
      handleAlert("warning", "Las contraseñas no coinciden.");
      return false;
    }
    return true;
  }

  if (type === "profile") {
    const first = form.querySelector('[name="first_name"]')?.value.trim();
    const last = form.querySelector('[name="last_name"]')?.value.trim();
    if (!first || !last) {
      handleAlert("warning", "Nombre y apellido son obligatorios.");
      return false;
    }
    return true;
  }

  return true;
}
