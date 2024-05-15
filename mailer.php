<?php

include_once "config/smtp.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}

if (array_key_exists('title', $_POST) && !IsNullOrEmptyString($_POST['title'])) {
    exit();
}

if (array_key_exists('email', $_POST)) {
    date_default_timezone_set('Etc/UTC');
    require './vendor/autoload.php';

	$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    $mail = new PHPMailer();
	$mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";

    $file_ok = true;
	if (array_key_exists('userfile', $_FILES)) {
		$ext = PHPMailer::mb_pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
		$uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['userfile']['name'])) . '.' . $ext;

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			if (!$mail->addAttachment($uploadfile, 'Vedhæftet_fil')) {
				$file_ok = false;
				$response = [
					"status" => false,
					"message" => 'Kunne ikke vedhæfte filen ' . $_FILES['userfile']['name']
				];
			}
		}
	}

	if ($file_ok)
	{
        $mail->Host = $smtp_host;
        $mail->Port = $smtp_port;
        $mail->SMTPAuth = $smtp_auth;  //Enable SMTP authentication
        $mail->Username = $smtp_usr;   //SMTP username
        $mail->Password = $smtp_pwd;   //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->setFrom($smtp_fromEmail, $smtp_fromName);
        $mail->addAddress($smtp_toEmail);
        
        if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
            $mail->Subject = "Du har fået ny besked";
            //Keep it simple - don't use HTML
            $mail->isHTML(false);
            //Build a simple message body
            $mail->Body = <<<EOT
                Nume: {$_POST['name']}
                Telefon: {$_POST['phone']}
                Email: {$_POST['email']}
                Mesaj: {$_POST['message']}
                EOT;
    
            //Send the message, check for errors
            if (!$mail->send()) {
                //The reason for failing to send will be in $mail->ErrorInfo
                //but it's unsafe to display errors directly to users - process the error, log it on your server.
                if ($isAjax) {
                    http_response_code(500);
                }
    
                $response = [
                    "status" => false,
                    "message" => 'Fejl ved afsendelse af beskeden.',
                    "debug" => $mail->ErrorInfo
                ];
            } else {
                $response = [
                    "status" => true,
                    "message" => 'Tak for beskeden!<br/>Jeg vender tilbage hurtigst muligt.'
                ];
            }
        } else {
            $response = [
                "status" => false,
                "message" => 'Email adressen er ikke gyldig!'
            ];
        }
    }

    if ($isAjax) {
        header('Content-type:application/json;charset=utf-8');
        echo json_encode($response);
        exit();
    }
}
?>