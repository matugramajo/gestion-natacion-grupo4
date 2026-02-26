## 🚀 Requisitos Previos

Para correr este proyecto localmente, necesitarás:
* **Laragon** (Recomendado) o XAMPP con PHP 8.1+
* **Git:** Necesario para el control de versiones. Podés descargarlo en [git-scm.com](https://git-scm.com/).
* **Composer:** (Si utilizás XAMPP, debés instalarlo manualmente. Laragon lo incluye por defecto).
* **MySQL/MariaDB**

## 🛠️ Configuración del Entorno

1. **Base de Datos:**
   * Abrí el Dashboard de **Laragon** y hacé clic en el botón **Database**. Se abrirá **phpMyAdmin**.
   * **Ayuda:** Si no tenés phpMyAdmin en tu Laragon, seguí este [tutorial de instalación](https://www.youtube.com/watch?v=0k1L655S3e8).
   * Creá una nueva base de datos llamada `swimming_pool`.
   * Entrá a la carpeta `database/` en la raíz del proyecto e importá el archivo `swimming_pool.sql`.

2. **Variables de Entorno y Archivos:**
   * Renombrá el archivo `.env.template` a `.env`.
   * Editá las variables según tu configuración local. Es fundamental configurar correctamente la `BASE_URL` (ej: `http://localhost/gestion-natacion`) para que la carga de imágenes sea correcta.
   * **Imágenes de Perfil:** El sistema gestiona las fotos en `public/img/uploads/profiles/swimmers/`. Asegurate de que el archivo `default-profile.png` esté presente en dicho directorio para evitar errores de carga.

## 📚 Documentación y Recursos Útiles

| Tecnología | Recurso | Utilidad |
| :--- | :--- | :--- |
| **Git** | [Git Documentation](https://git-scm.com/doc) | Guía de comandos básicos y flujo de trabajo. |
| **PHP** | [Manual Oficial](https://www.php.net/manual/es/) | Consulta de funciones y PDO. |
| **JavaScript** | [MDN Web Docs](https://developer.mozilla.org/es/docs/Web/JavaScript) | Guía de JS y Fetch API. |
| **Bootstrap 5** | [W3Schools BS5](https://www.w3schools.com/bootstrap5/) | Referencia de componentes y grilla. |
| **Diseño** | [Coolors.co](https://coolors.co/) | Generación de paletas de colores. |

## 🔌 Extensiones Recomendadas (VS Code)

Para un entorno de desarrollo fluido y evitar errores comunes, se recomienda instalar:
* **PHP Intelephense**: Autocompletado profesional y navegación de código.
* **PHP Debug**: Para realizar debugging real junto con Xdebug (incluido en Laragon).
* **Auto Rename Tag**: Mantiene etiquetas HTML sincronizadas automáticamente.
* **Thunder Client**: Cliente ligero para testear peticiones API/Fetch desde el editor.

## 🌟 Recursos "PRO" (Desafíos Sugeridos)

Para quienes deseen profundizar, recomendamos explorar estas librerías profesionales:
* **Alertas:** [SweetAlert2](https://sweetalert2.github.io/) (Ya implementado) y [Toastr](https://codeseven.github.io/toastr/).
* **Imágenes:** [Cropper.js](https://fengyuanchen.github.io/cropperjs/) para recorte y ajuste de fotos de perfil.
* **Tablas:** [DataTables](https://datatables.net/) para gestión avanzada de listados y búsquedas.

---
*Este proyecto busca simular un entorno profesional de desarrollo. El uso de Git y la lectura de documentación técnica son parte integral de la formación.*