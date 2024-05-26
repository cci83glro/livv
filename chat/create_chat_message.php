<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

if(Input::exists()) {
    $message = Input::get['message'];

    $query = 'INSERT INTO chat_messages(user_id, `message`) VALUES(?,?)';

    if($dbo->query($query, $user_id, $message)) {
        // Booking successful
        echo "success";
    } else {
        // Handle errors
        echo "Error creating booking: " . $dbo->error;
    }
}

?>
