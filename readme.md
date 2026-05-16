# 🏊 Sistema de Gestión - Natación

Este es un entorno de desarrollo profesional diseñado para la gestión de alumnos y entrenamientos. El proyecto utiliza una arquitectura MVC (Modelo-Vista-Controlador) y ruteo dinámico.

## 🚀 Requisitos Previos

Para correr este proyecto localmente, necesitarás:
* **XAMPP** con PHP 8.1+: Servidor local para Apache y MySQL. [Descargar aquí](https://www.apachefriends.org/es/index.html).
* **Git:** Para el control de versiones. [Descargar aquí](https://git-scm.com/).
* **Composer:** Gestor de dependencias. [Descargar aquí](https://getcomposer.org/).

## 🛠️ Configuración del Entorno (XAMPP)

1. **Ubicación del Proyecto (¡CRÍTICO!):**
   * El proyecto debe clonarse directamente en `C:\xampp\htdocs\`.
   * **Estructura correcta:** `C:\xampp\htdocs\gestion-natacion\`
   * ⚠️ **Atención:** Si al entrar a la carpeta ves otra carpeta llamada igual (`gestion-natacion/gestion-natacion`), el ruteo **no funcionará**. Los archivos deben estar en el primer nivel de la carpeta del proyecto.

2. **Base de Datos:**
   * Abrí el **XAMPP Control Panel** e iniciá Apache y MySQL.
   * Entrá a **phpMyAdmin** (botón Admin de MySQL).
   * Creá la base de datos `swimming_pool_grupo4`.
   * Importá el archivo ubicado en `database/swimming_pool_grupo4.sql`.
   * **Nota:** Verificá que la tabla `password_resets` incluya la columna `expires_at`.

3. **Variables de Entorno (.env):**
   * Renombrá `.env.template` a `.env`.
   * Configurá `APP_URL="http://localhost/gestion-natacion"` (**sin barra final y sin /public**).
   * Completá los datos de SMTP (Host, User, Password) para habilitar el recupero de contraseña.

4. **Configuración de Apache:**
   * En el Panel de XAMPP, clic en **Config** (Apache) -> **httpd.conf**.
   * Buscá la sección `<Directory "C:/xampp/htdocs">`.
   * Cambiá `AllowOverride None` por **`AllowOverride All`**.
   * Guardá y **REINICIÁ Apache**.

## 🔌 Herramientas de Testeo (Postman)

Recomendamos usar **Postman** para testear las respuestas del servidor:
* **Ejemplo de uso:** * Tipo de petición: **POST**
  * URL: `http://localhost/gestion-natacion/?url=login`
  * Body: Seleccioná `form-data` y agregá los campos `email` y `password`.
  * Esto te permitirá ver la respuesta JSON pura antes de procesarla en el frontend.

## 📚 Documentación y Recursos Útiles

| Tecnología | Recurso | Utilidad |
| :--- | :--- | :--- |
| **Git** | [Git Documentation](https://git-scm.com/doc) | Guía de comandos y flujo. |
| **PHP** | [Manual Oficial](https://www.php.net/manual/es/) | Funciones y manejo de PDO. |
| **JavaScript** | [MDN Web Docs](https://developer.mozilla.org/es/docs/Web/JavaScript) | Fetch API y manipulación del DOM. |
| **Bootstrap 5** | [W3Schools BS5](https://www.w3schools.com/bootstrap5/) | Grillas y componentes. |

## 🌟 Recursos "PRO" (Desafíos Sugeridos)

Para quienes deseen profundizar, recomendamos explorar estas librerías profesionales:
* **Alertas:** [SweetAlert2](https://sweetalert2.github.io/) (Ya implementado) y [Toastr](https://codeseven.github.io/toastr/).
* **Imágenes:** [Cropper.js](https://fengyuanchen.github.io/cropperjs/) para recorte de fotos de perfil.
* **Tablas:** [DataTables](https://datatables.net/) para gestión avanzada de listados.

---
*Este proyecto simula un entorno laboral real. La correcta configuración del entorno es el primer paso de todo desarrollador profesional.*