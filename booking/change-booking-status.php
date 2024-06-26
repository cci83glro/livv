<?php

$public = false;
$permissions = [2];
require_once __DIR__.'/../master-pages/master.php';

if (!isset($_POST['booking_id']) || !isset($_POST['new_status_id'])) {
    die("Booking ID eller Bruger ID mangler.");
}

$booking_id = $_POST['booking_id'];
$new_status_id = $_POST['new_status_id'];
$email = $_POST['email'];

$dbo = dbo::getInstance();

if ($dbo->query("UPDATE bookings SET status_id = $new_status_id WHERE booking_id = $booking_id")) {
    if ($new_status_id == 20) {
        try {
            $body = get_email_body('_email_complete_booking_notify_district.php');
            $body = str_replace("{{booking_id}}", $booking_id, $body);
            $body = str_replace("{{bookings_page_link}}", $bookings_page_url."?searchText=".$booking_id, $body);
            send_email($email, $site_name.' - En af dine bookings er markeret som klar', $body);
        }
        catch(Exception $e) {
            echo $e;
        }
    }

    echo "ok";
} else {
    echo "Error updating booking: " . $dbo->error;
}

?>