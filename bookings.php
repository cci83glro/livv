
<?php
	$public = false;
	$permissions = [1, 2, 3];
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

    $query = $db->query("SELECT id, CONCAT(fname, ' ', lname) as name
        FROM livv.users WHERE permissions = 3");
    $employees = $query->results();
?>

<main>
	<!-- Banner -->
	<section class="section page-banner position-relative" style="background-image: url(assets/images/booking-page-banner.webp);">
		<div class="r-container">
			<div class="image-overlay"></div>
			<div class="page-title position-relative" style="z-index: 2;">
				<h1 class="font-1 fw-bold text-white">Bookings</h1>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="r-container">
			<div class="d-flex flex-column gap-3">
				<div id="add-booking-button-wrapper" class="form-actions">
					<button id="add-booking-button" class="save no-margin" onclick="showAddBookingForm()"><i class="fa fa-plus"></i> Tilføj booking</button>
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