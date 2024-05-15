<?php

// $lang = [];
// if (file_exists($abs_us_root . $us_url_root . 'usersc/includes/custom_functions.php')) {
//   require_once $abs_us_root . $us_url_root . 'usersc/includes/custom_functions.php';
// }

// $usplugins = parse_ini_file($abs_us_root . $us_url_root . 'usersc/plugins/plugins.ini.php', true);
// foreach ($usplugins as $k => $v) {
//   if ($v == 1) {
//     if (file_exists($abs_us_root . $us_url_root . 'usersc/plugins/' . $k . '/override.php')) {
//       include $abs_us_root . $us_url_root . 'usersc/plugins/' . $k . '/override.php';
//     }
//   }
// }

// require_once $abs_us_root . $us_url_root . 'users/helpers/us_helpers.php';
// require_once $abs_us_root . $us_url_root . 'users/helpers/class.treeManager.php';
// require_once $abs_us_root . $us_url_root . 'users/helpers/menus.php';
// require_once $abs_us_root . $us_url_root . 'users/helpers/permissions.php';
// require_once $abs_us_root . $us_url_root . 'users/helpers/users.php';
// require_once $abs_us_root . $us_url_root . 'users/helpers/dbmenu.php';
//require_once  "../config/smtp.php";

// if (file_exists($abs_us_root . $us_url_root . 'usersc/vendor/autoload.php')) {
//   require_once $abs_us_root . $us_url_root . 'usersc/vendor/autoload.php';
// }

// if (file_exists($abs_us_root . $us_url_root . 'users/vendor/autoload.php')) {
//   require_once $abs_us_root . $us_url_root . 'users/vendor/autoload.php';
// }

//require $abs_us_root . $us_url_root . 'users/classes/phpmailer/PHPMailerAutoload.php';



use PHPMailer\PHPMailer\PHPMailer;

if (!function_exists('send_email')) {
  function send_email($to, $subject, $body, $opts = [], $attachment = null)
  {
    global $abs_us_root, $us_url_root;

    include_once $abs_us_root . $us_url_root . "config/smtp.php";

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

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = "base64";
    //$mail->SMTPDebug = $results->debug_level;
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
    $result = $mail->send();

    return $result;
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
