<?php
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
 {
    public function sendEmailResetPassword( $toEmail, $token )
 {
        $colorPrincipal = '#007bff';
        $colorFondo = '#f4f7f9';

        $mail = new PHPMailer( true );

        try {
            $mail->isSMTP();
            $mail->Host       = Env::get( 'MAIL_HOST' );
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::get( 'MAIL_USERNAME' );
            $mail->Password   = Env::get( 'MAIL_PASSWORD' );
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int) $_ENV['MAIL_PORT'];

            $mail->setFrom( Env::get( 'MAIL_FROM' ), 'Soporte Escuela de Natación' );
            //$mail->setFrom( 'lic.juanpablocesarini@gmail.com', 'Escuela de Natación' );
            $mail->addAddress( $toEmail );

            $mail->isHTML( true );
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperación de contraseña';

            $baseUrl = Env::get( 'APP_URL' );
            $resetLink = rtrim( $baseUrl, '/' ) . '/index.php?url=reset-password&token=' . $token;

            // Armamos el Body con un formato más robusto
            $mail->Body = "
<div style='background-color: {$colorFondo}; padding: 40px; font-family: Arial, sans-serif; line-height: 1.6;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e1e8ed;'>
        
        <div style='background-color: {$colorPrincipal}; padding: 20px; text-align: center;'>
            <h1 style='color: #ffffff; margin: 0; font-size: 24px;'>Escuela de Natación</h1>
        </div>

        <div style='padding: 30px; text-align: center;'>
            <h2 style='color: #333333;'>¿Olvidaste tu contraseña?</h2>
            <p style='color: #666666; font-size: 16px;'>
                No te preocupes, nos pasa a todos. Haz clic en el botón de abajo para elegir una nueva clave y volver al agua.
            </p>
            
            <div style='margin: 30px 0;'>
                <a href='{$resetLink}' style='background-color: {$colorPrincipal}; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                    Restablecer Contraseña
                </a>
            </div>

            <p style='color: #999999; font-size: 12px; margin-top: 30px;'>
                Si no solicitaste este cambio, puedes ignorar este correo con seguridad. 
                El enlace expirará automáticamente en 1 hora.
            </p>
        </div>

        <div style='background-color: #f8f9fa; padding: 15px; text-align: center; border-top: 1px solid #eeeeee;'>
            <p style='color: #aaaaaa; font-size: 11px; margin: 0;'>
                © " . date( 'Y' ) . " Escuela de Natación - Panel Administrativo
            </p>
        </div>
    </div>
</div>
";
           // $mail->SMTPDebug = 3;
            // Nivel 3 es más detallado
         //   $mail->Debugoutput = 'html';
            // Para que se vea bien en el navegador
            $mail->send();
            return true;
        } catch ( Exception $e ) {
            error_log( 'PHPMailer: ' . $mail->ErrorInfo . ' | ' . $e->getMessage() );
            return false;
        }
    }

    public function sendWelcomeCoach( $toEmail, $firstName, $tempPassword ) {
        $colorPrincipal = '#1a6cf6';
        $colorFondo     = '#f4f7f9';
        $mail = new PHPMailer( true );

        try {
            $mail->isSMTP();
            $mail->Host       = Env::get( 'MAIL_HOST' );
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::get( 'MAIL_USERNAME' );
            $mail->Password   = Env::get( 'MAIL_PASSWORD' );
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) Env::get( 'MAIL_PORT', 587 );

            $mail->setFrom( Env::get( 'MAIL_FROM' ), 'SwimManager' );
            $mail->addAddress( $toEmail );
            $mail->isHTML( true );
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Bienvenido al equipo — tus credenciales de acceso';

            $loginUrl = rtrim( Env::get( 'APP_URL' ), '/' ) . '/?url=login';
            $mail->Body = "
<div style='background-color:{$colorFondo};padding:40px;font-family:Arial,sans-serif;line-height:1.6;'>
  <div style='max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;border:1px solid #e1e8ed;'>
    <div style='background-color:{$colorPrincipal};padding:20px;text-align:center;'>
      <h1 style='color:#fff;margin:0;font-size:24px;'>SwimManager</h1>
    </div>
    <div style='padding:30px;text-align:center;'>
      <h2 style='color:#333;'>¡Bienvenido, {$firstName}!</h2>
      <p style='color:#666;font-size:16px;'>Tu perfil de coach fue creado. Estas son tus credenciales:</p>
      <div style='background:#f4f7f9;border-radius:8px;padding:20px;margin:20px 0;text-align:left;'>
        <p style='margin:5px 0;'><strong>Email:</strong> {$toEmail}</p>
        <p style='margin:5px 0;'><strong>Contraseña provisoria:</strong> {$tempPassword}</p>
      </div>
      <a href='{$loginUrl}' style='background:{$colorPrincipal};color:#fff;padding:15px 25px;text-decoration:none;border-radius:5px;font-weight:bold;display:inline-block;'>Ingresar al sistema</a>
    </div>
  </div>
</div>";
            $mail->send();
            return true;
        } catch ( Exception $e ) {
            error_log( 'PHPMailer coach welcome: ' . $mail->ErrorInfo . ' | ' . $e->getMessage() );
            return false;
        }
    }

    /** Alias usado por AdminController */
    public function sendCoachWelcome( $toEmail, $firstName, $tempPassword ) {
        return $this->sendWelcomeCoach( $toEmail, $firstName, $tempPassword );
    }
}