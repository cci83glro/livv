
<?php
	$public = false;
	$permissions = [1, 2, 3];
	include_once __DIR__."/master-pages/header.php";

	if ($user_id == 0) {
		die("Invalid token");
	}
	
    $db = DB::getInstance();

    $times = $db->query("SELECT time_id, time_value FROM Times")->results();
    $shifts = $db->query("SELECT shift_id, shift_name FROM Shifts")->results();
    $qualifications = $db->query("SELECT qualification_id, qualification_name FROM Qualifications")->results();
	$employees = $db->query("SELECT id, CONCAT(fname, ' ', lname) as name FROM livv.users WHERE permissions = 3")->results();
	$districts = $db->query("SELECT * FROM districts ORDER BY district_name ASC")->results();
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
				<?php if ($user_permission == 1 || $user_permission == 2) { ?>
					<div id="add-booking-button-wrapper" class="form-actions">
						<button id="add-booking-button" class="save no-margin" onclick="showAddBookingForm()"><i class="fa fa-plus"></i> Tilføj booking</button>
					</div>
					<?php include_once "booking/add-booking-form.php"; ?>
					<div id="filter-wrapper" class="form-actions align-left">
						<span>Søg efter booking ID </span><input id="booking-id-filter" class="form-control w-10p" placeholder="Indtast ID">
						<button id="search" class="save w-10p" onclick="filterBookingsById()">Søg</button>
						<button id="search" class="cancel w-10p" onclick="resetBookingsFilter()">Nulstil</button>
					</div>
					<div class="accordion-custom d-flex flex-column gap-2" id="bookings-container" data-active-page=""></div>
					<div class="pagination" id="pagination"></div>
				<?php } ?>

				<?php if ($user_permission == 3) { ?>
					<div id="filter-wrapper" class="form-actions align-left">
						<span>Søg efter booking ID </span><input id="booking-id-filter" class="form-control w-10p" placeholder="Indtast ID">
						<button id="search" class="save w-10p" onclick="filterEmployeeBookingsById()">Søg</button>
						<button id="search" class="cancel w-10p" onclick="resetEmployeeBookingsFilter()">Nulstil</button>
					</div>
					<h2>Mine bookings</h2>
					<div class="accordion-custom d-flex flex-column gap-2" id="my-bookings-container" data-active-page=""></div>
					<div class="pagination" id="my-bookings-pagination"></div>
					<h2>Ledige bookings</h2>
					<div class="accordion-custom d-flex flex-column gap-2" id="available-bookings-container" data-active-page=""></div>
					<div class="pagination" id="available-bookings-pagination"></div>
				<?php } ?>
			</div>
		</div>
	</section>
</main>

<?php include_once __DIR__."/master-pages/footer.php"?>
<script src="assets/js/bookings.js"></script>

</body>
</html>