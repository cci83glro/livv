<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

if(Input::get('message')) {
    if ($user_id < 1) {
        echo('Ugyldig bruger');
        die();
    }

    $dbo = dbo::getInstance();
    $message = Input::get('message');

    $query = 'INSERT chat_messages(user_id, `message`) VALUES(?,?)';

    if($dbo->query($query, $user_id, $message)) {
        echo "success";
    } else {
        echo "Beskeden kunne ikke sendes: " . $dbo->error;
    }

    $options = ['permission' => '(3)', 'active' => 1];

    $filtered = array_filter(getUsers($dbo, $options), function($obj) use ($user_id) {
        return $obj['id'] != $user_id;
    });
     
    $recipients = array_map(function($obj) {
        return $obj['email'];
    }, $filtered);

    try {
        $body = get_email_body('_email_new_chat_message_notify_users.php');
        $body = str_replace("{{chat_page_url}}", $chat_page_url, $body);
        send_email($recipients, $site_name.' - Der er nye chat beskeder', $body);
    }
    catch(Exception $e) {
        echo $e;
    }
}

?>
