<?php

$public = false;
$permissions = [2,3];
require_once __DIR__.'/../master-pages/master.php';

// Check if booking ID and user ID are provided
if (!isset($_POST['booking_id'])) {
    die("Booking ID mangler.");
}

$booking_id = $_POST['booking_id'];

$fname = $user_fname;
$lname = $user_lname;
$email = $user_email;
$assigned_user_id = $user_id;

if ($user_permission == 2) {    
    $bookings_data = getAssignedUserDetailsForBooking($booking_id);
    if (count($bookings_data) < 1) {
        die("Booking/bruger findes ikke");
        Redirect::to($bookings_page_url);
    }

    $booking_data = $bookings_data[0];
    $fname = $booking_data['fname'];
    $lname = $booking_data['lname'];
    $email = $booking_data['email'];
    $assigned_user_id = $booking_data['assigned_user_id'];
}

$dbo = dbo::getInstance();

if ($dbo->query("UPDATE bookings SET assigned_user_id = NULL WHERE booking_id = $booking_id")) {
    
    $body = get_email_body('_email_booking_unassign_notify_user.php');
    $body = str_replace("{{fname}}", $fname, $body);
    $body = str_replace("{{lname}}", $lname, $body);
    $body = str_replace("{{booking_id}}", $booking_id, $body);
    $body = str_replace("{{bookings_page_link}}", $bookings_page_url.'?searchText='.$booking_id, $body);
    send_email($email, 'Unassignment af '.$site_name.' booking', $body);

    if ($user_id == $assigned_user_id) {
        $body = get_email_body('_email_booking_unassign_notify_admins.php');
        $body = str_replace("{{fname}}", $fname, $body);
        $body = str_replace("{{lname}}", $lname, $body);
        $body = str_replace("{{booking_id}}", $booking_id, $body);
        $body = str_replace("{{bookings_page_link}}", $bookings_page_url.'?searchText='.$booking_id, $body);
        send_email($admin_email_list, 'Booking unassignet fra vikar', $body);
    }

    echo "User unassigned successfully!";
} else {
    echo "Error unassigning user: " . $dbo->error;
}

?>