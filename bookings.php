
<?php

	$public = false;
	$permissions = [1, 2, 3];
	$pageTitle = 'Vagter';
	include_once __DIR__."/master-pages/header.php";

	if ($user_id == 0) {
		echo('Invalid token');
		die("Invalid token");
	}
	
    $dbo = dbo::getInstance();

    $times = $dbo->query("SELECT time_id, time_value FROM times")->fetchAll();
    $shifts = $dbo->query("SELECT shift_id, shift_name FROM shifts")->fetchAll();
    $qualifications = $dbo->query("SELECT qualification_id, qualification_name FROM qualifications")->fetchAll();
	$employees = $dbo->query("SELECT id, CONCAT(fname, ' ', lname) as name FROM uacc WHERE permissions = 3")->fetchAll();
	$districts = $dbo->query("SELECT * FROM districts ORDER BY district_name ASC")->fetchAll();
?>

<main>
	<section class="section page-banner position-relative" style="background-image: url(<?=$us_url_root.'/assets/images/booking-page-banner.webp'?>);">
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
					<?php include_once __DIR__."/booking/add-booking-form.php"; 
				} ?>
				
				<div id="filter-wrapper" class="form-actions align-left">
					
					<div class="filter-option">
						<p>Vis</p>
						<select onchange="onDisplayTypeChange()" class="form-control dropdown" name="bookings-type" id="filter-bookings-type">
							<option value="coming">Kommende bookings</option>
							<option value="passed">Tidligere bookings</option>
							<option value="terminated">Bestilte bookings</option>
							<?php if ($user_permission == 2) { ?>
								<option value="reports">Rapport</option>
							<?php } ?>
						</select>
						<span class="hidden" id="active-bookings-type"></span>
					</div>

					<?php if ($user_permission == 3) { ?>
						<div class="filter-option">
						<p>Vis</p>
						<select class="form-control dropdown" name="whose-bookings" id="filter-whose-bookings">
							<option value="mine">Mine bookings</option>
							<option value="available">Tilgængelige bookings</option>
						</select>
						<span class="hidden" id="active-whose-bookings"></span>
					</div>
					<?php } ?>

					<div class="filter-option" id="free-text-search">
						<p>Fritekst søgning</p>
						<input id="search-text" class="form-control" placeholder="">
						<span class="hidden" id="active-search-text"></span>
					</div>

					<br/>

					<?php if ($user_permission == 2 || $user_permission == 3) { ?>
						<div class="filter-option" id="filter-districts">
							<p>Kommune</p>
							<select class="form-control dropdown" name="district-id" id="filter-district-id" placeholder="Vælg kommune">
								<option value="">Alle</option>
								<?php 
									foreach($districts as $district){
										echo "<option value='".$district['district_id']."'>".$district['district_name']."</option>"; 
									}
								?>
							</select>
							<span class="hidden" id="active-district-id"></span>
						</div>
					<?php } ?>

					<?php if ($user_permission == 2) { ?>
						<div class="filter-option" id="filter-employees">
							<p>Ansat</p>
							<select class="form-control dropdown" name="employee-id" id="filter-employee-id" placeholder="Vælg ansat">
								<option value="">Alle</option>
								<?php 
									foreach($employees as $employee){
										echo "<option value='".$employee['id']."'>".$employee['name']." (Lønnummer: ".$employee['id'].")</option>";
									}   
								?>
							</select>
						</div>
					<?php } ?>

					<br/>

					<?php if ($user_permission == 1 || $user_permission == 2) { ?>
						<div class="filter-option" id="filter-qualifications">
							<p>Uddannelse</p>
							<select class="form-control dropdown" name="qualification-id" id="filter-qualification-id">
								<option value="">Alle</option>
								<?php 
									foreach($qualifications as $qualification){
										echo "<option value='".$qualification['qualification_id']."'>".$qualification['qualification_name']."</option>";
									}            
								?>
							</select>
							<span class="hidden" id="active-qualification-id"></span>
						</div>
					<?php } ?>
					
					<?php if ($user_permission == 1 || $user_permission == 2) { ?>
						<div class="filter-option" id="filter-shifts">
							<p>Stilling</p>
							<select class="form-control dropdown" name="shift-id" id="filter-shift-id" >
								<option value="">Alle</option>
								<?php 
									foreach($shifts as $shift){
										echo "<option value='".$shift['shift_id']."'>".$shift['shift_name']."</option>";
									}            
								?>
							</select>
							<span class="hidden" id="active-shift-id"></span>
						</div>
						<br/>
					<?php } ?>

					<div class="filter-option" id="filter-from-date" data-actual-date=''>
						<p>Fra dato</p>
						<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control date-picker" id="filter-from-date-value" name="from-date" placeholder="Fra dato" data-actual-date="">
						<span class="hidden" id="active-from-date"></span>
					</div>

					<div class="filter-option" id="filter-to-date" data-actual-date=''>
						<p>Til dato</p>
						<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control date-picker" id="filter-to-date-value" name="to-date" placeholder="Til dato" data-actual-date="">
						<span class="hidden" id="active-to-date"></span>
					</div>

					<div class="filter-option" id="filter-from-start-time">
						<p>Fra starttid</p>							
						<select class="form-control dropdown" name="from-time-id" id="filter-from-time-id" >
							<option value="">Alle</option>
							<?php 
								foreach($times as $time){
									echo "<option value='".$time['time_id']."'>".$time['time_value']."</option>"; 
								}
							?>
						</select>
						<span class="hidden" id="active-from-time-id"></span>
					</div>

					<div class="filter-option" id="filter-to-start-time">
						<p>Til starttid</p>
						<select class="form-control dropdown" name="to-time-id" id="filter-to-time-id" >
							<option value="">Alle</option>
							<?php 
								foreach($times as $time){
									echo "<option value='".$time['time_id']."'>".$time['time_value']."</option>"; 
								}
							?>
						</select>
						<span class="hidden" id="active-to-time-id"></span>
					</div>

					<br/>

					<button id="search" class="save" onclick="filterBookings()">Søg</button>
					<button id="search" class="cancel" onclick="resetBookingsFilter()">Nulstil</button>
					<p id="total-hours"></p>
				</div>
				<div class="accordion-custom d-flex flex-column gap-2" id="bookings-container" data-active-page=""></div>
				<div class="pagination" id="pagination"></div>

			</div>
		</div>
	</section>
</main>

<?php include_once __DIR__."/master-pages/footer.php"?>
<script src="assets/js/bookings.js"></script>

</body>
</html>