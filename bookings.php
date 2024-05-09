
<?php
	include_once "header.php";

	if ($user_id == 0) {
		die("Invalid token");
	}
	
    $db = DB::getInstance();
    
    $query = $db->query("SELECT time_id, time_value FROM Times");
    $times = $query->results();

    $query = $db->query("SELECT shift_id, shift_name FROM Shifts");
    $shifts = $query->results();

    $query = $db->query("SELECT qualification_id, qualification_name FROM Qualifications");
    $qualifications = $query->results();

    $query = $db->query("SELECT u.id, CONCAT(u.fname, ' ', u.lname) as name
        FROM livv.users u INNER JOIN user_permission_matches up ON u.id=up.user_id
        WHERE up.permission_id = 3");
    $employees = $query->results();
?>

<main>
        <!-- Banner -->
        <section class="section position-relative" style="background-image: url(assets/images/booking-page-banner.webp);">
            <div class="r-container">
                <div class="image-overlay"></div>
                <div class="position-relative" style="z-index: 2;">
                    <h1 class="font-1 fw-bold text-white">Bookings</h1>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="r-container">
                <div class="d-flex flex-column gap-3">
                    
					<!-- <h3 class="font-1 fw-bold">Frequently Asked Questions</h3> -->
					<div id="add-booking-button-wrapper">
						<button id="add-booking-button" onclick="showAddBookingForm()">+ Tilføj booking</button>
					</div>
					<?php include_once "booking/add-booking-form.php"; ?>
					<div class="accordion-custom d-flex flex-column gap-2" id="bookings-container" data-active-page="">								
					</div>
					<div id="pagination"></div>
                </div>
            </div>
        </section>
    </main>

<?php include_once "footer.php"?>
<script src="assets/js/bookings.js"></script>

</body>
</html>