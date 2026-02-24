import { initLogin } from "./auth/formLogin.js";
import { initRegister } from "./auth/formRegister.js";
import { initForgotPassword } from "./auth/formForgotPassword.js";
import {initResetPassword} from "./auth/formResetPassword.js"
// Iniciamos cada funcionalidad de forma independiente
document.addEventListener("DOMContentLoaded", () => {
  initLogin();
  initRegister();
  initForgotPassword();
  initResetPassword();
});
