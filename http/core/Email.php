<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
	//Email de Contato
	public static function defaultSend($data)
	{
		$data['datahora'] = date("d/m/Y H:i:s");

		//var_dump($data);

		$message = 'Texto';

		$mail = new PHPMailer(true);
		try {
			//Server settings
			/* Set mailer to use SMTP
			 * 0 = off (for production use)
             * 1 = client messages
             * 2 = client and server messages
			*/
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = 'dominio.com.br';
			$mail->SMTPAuth = true;
			$mail->Username = 'emailQueEstaEnviando@dominio.com.br';
			$mail->Password = 'senha';
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;
			//$mail->WordWrap = 70;


			//Recipients
			$mail->setFrom('emailQueEstaEnviandoPodeSerOutro@dominio.com.br', 'Formulario Contato');
			//$mail->addAddress('info@example.com', 'Contact');
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

			//Content
			$mail->isHTML(true);
			$mail->Subject = html_entity_decode($data['assunto'], ENT_QUOTES, 'UTF-8');
			$mail->Body    = $message;
			$mail->AltBody = $data['mensagem'];
			$mail->CharSet = 'utf-8';
			$mail->SetLanguage("br");

			return $mail->send();
		} catch (Exception $e) {
			return $errorEmail = false;
		}
	}
}
