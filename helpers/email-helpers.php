<?php

use PHPMailer\PHPMailer\PHPMailer;
global $abs_us_root, $us_url_root;
include_once $abs_us_root . $us_url_root . "config/smtp.php";

if (!function_exists('send_email')) {
  function send_email($to, $subject, $body, $opts = [], $attachment = null)
  {
    /*
    As of v5.6, $to can now be an array of email addresses
    you can now pass in
    $opts = array(
      'email' => 'from_email@aol.com',
      'name'  => 'Bob Smith',
      'cc'    => 'cc@example.com',
      'bcc'   => 'bcc@example.com'
    );
    */

    global $smtp_host, $smtp_port, $smtp_auth, $smtp_usr, $smtp_pwd, $smtp_secure, $smtp_fromEmail, $smtp_fromName;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = "base64";
    $mail->SMTPDebug = true;
    $mail->Host = $smtp_host;
    $mail->Port = $smtp_port; 
    $mail->SMTPAuth = $smtp_auth;
    $mail->Username = $smtp_usr;
    $mail->Password = $smtp_pwd;
    $mail->SMTPSecure = $smtp_secure;
    //$mail->AuthType = 'CRAM-MD5'; 
  
    if ($attachment != false) {
      $mail->addAttachment($attachment);
    }

    if (isset($opts['email']) && isset($opts['name'])) {
      $mail->setFrom($opts['email'], $opts['name']);
    } else {
      $mail->setFrom($smtp_fromEmail, $smtp_fromName);
    }

    if (isset($opts['cc'])) {
      $mail->addCC($opts['cc']);
    }

    if (isset($opts['bcc'])) {
      $mail->addBCC($opts['bcc']);
    }

    if (is_array($to)) {
      foreach ($to as $t) {
        $mail->addAddress(rawurldecode($t));
      }
    } else {
      $mail->addAddress(rawurldecode($to));
    }
    $mail->isHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body    = $body;
    if (!empty($attachment)) $mail->addAttachment($attachment);
    if (!$mail->send()) {
      log_error($mail->ErrorInfo);
      throw new Exception($mail->ErrorInfo);
      return 'error';
    }

    return 'ok';
  }
}

if (!function_exists('get_email_body')) {
  function get_email_body($template)
  {
    global $abs_us_root, $us_url_root;
    $template_path = $abs_us_root . $us_url_root . 'mails/um/' . $template;
    ob_start();
    if (file_exists($template_path)) {
      require $template_path;
    }

    return ob_get_clean();
  }
}
