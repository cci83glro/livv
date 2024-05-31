<?php
session_start();
$public = true;
require_once __DIR__.'/../master-pages/master.php';

if (array_key_exists('title', $_POST) && !IsNullOrEmptyString($_POST['title'])) {
    exit();
}

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$token = $_POST['csrf'];
if (!Token::check($token)) {
    $response = [
        "status" => false,
        "message" => 'Der er sket en session fejl.'
    ];
} else if (array_key_exists('email', $_POST)) {

    $body = get_email_body('_email_contact_form.php');
    $body = str_replace("{{name}}", $_POST['name'], $body);
    $body = str_replace("{{email}}", $_POST['email'], $body);
    $body = str_replace("{{phone}}", $_POST['phone'], $body);
    $body = str_replace("{{subject}}", $_POST['subject'], $body);
    $body = str_replace("{{message}}", $_POST['message'], $body);
    $result = send_email('kontakt@livvikar.dk', 'Ny besked via kontaktformularen', $body);

    if ($result=='ok') {
        $response = [
            "status" => true,
            "message" => 'Tak for din besked!<br/>Vi vender tilbage hurtigst muligt.'
        ];
    } else if ($result=='error') {
        $response = [
            "status" => false,
            "message" => 'Der er sket en fejl.'
        ];
    }
}

if ($isAjax) {
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($response);
    exit();
}

?>