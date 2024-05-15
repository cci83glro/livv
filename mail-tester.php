<?php
$public = true;

require_once 'header.php';

            $user_url = 'localhost'.$user_page_url.'30';
            $body = get_email_body('_email_new_account_notify_admins.php');
            $body = str_replace("{{user_url}}", $user_url, $body);
            send_email($admin_email_list, 'Ny vikar konto til aktivering', $body);
?>
