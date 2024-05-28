<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

// Check if booking ID and user ID are provided
if (!isset($_POST['booking_id']) || !isset($_POST['user_id'])) {
    die("Booking ID eller User ID mangler.");
}

// Retrieve booking ID and user ID from frontend
$booking_id = $_POST['booking_id'];
$assigned_user_id = $_POST['user_id'];

if ($user_permission != 2 && $assigned_user_id != $user_id) {
    die("Vikarerne kan ikke assigne bookings til andre vikarer.");
}

$fname = $user_fname;
$lname = $user_lname;
$email = $user_email;

if ($user_permission == 2) {
    $users_data = getUsers($assigned_user_id);
    if (count($users_data) < 1) {
        die("Brugeren findes ikke");
        Redirect::to($bookings_page_url);
    }

    $user_data = $users_data[0];
    $fname = $user_data->fname;
    $lname = $user_data->lname;
    $email = $user_data->email;
}

$dbo = dbo::getInstance();

if ($dbo->query("UPDATE bookings SET assigned_user_id = $assigned_user_id WHERE booking_id = $booking_id")) {

    $body = get_email_body('_email_booking_assign_notify_user.php');
    $body = str_replace("{{fname}}", $fname, $body);
    $body = str_replace("{{lname}}", $lname, $body);
    $body = str_replace("{{booking_id}}", $booking_id, $body);
    $body = str_replace("{{bookings_page_link}}", $url_host.$bookings_page_url, $body);
    send_email($email, 'Ny LivVikar booking assignet til dig', $body);

    if ($user_id == $assigned_user_id) {
        $body = get_email_body('_email_booking_assign_notify_admins.php');
        $body = str_replace("{{fname}}", $fname, $body);
        $body = str_replace("{{lname}}", $lname, $body);
        $body = str_replace("{{booking_id}}", $booking_id, $body);
        $body = str_replace("{{bookings_page_link}}", $url_host.$bookings_page_url, $body);
        send_email($admin_email_list, 'Booking assignet til vikar', $body);
    }
    echo "success";
} else {
    echo "Error assigning user: " . $dbo->error;
}

?>