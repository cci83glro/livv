
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
				}
				?>
				
				<div id="filter-wrapper" class="form-actions align-left">
					<span class="hidden" id="active-bookings-type"></span>
					<span class="hidden" id="active-district-id"></span>
					<span class="hidden" id="active-qualification-id"></span>
					<span class="hidden" id="active-shift-id"></span>
					<span class="hidden" id="active-from-date"></span>
					<span class="hidden" id="active-to-date"></span>
					<span class="hidden" id="active-from-time-id"></span>
					<span class="hidden" id="active-to-time-id"></span>
					<span class="hidden" id="active-search-text"></span>
					
					<div class="filter-option">
						<p>Vis</p>
						<select class="form-control dropdown py-2 px-4" name="bookings-type" id="filter-bookings-type">
							<option value="coming">kommende</option>
							<option value="passed">tidligere</option>
							<option value="terminated">bestilte</option>
						</select>
					</div>
					<br/>
					<div class="filter-option">
						<p>Fritekst søgning</p>
						<input id="search-text" class="form-control" placeholder="">
					</div>
					<?php if ($user_permission == 1 || $user_permission == 2) { ?>
						<?php if ($user_permission == 2) { ?>
						<div class="filter-option">
							<p>Kommune</p>
							<select class="form-control dropdown py-2 px-4" name="district-id" id="filter-district-id" placeholder="Vælg kommune">
								<option value="">Vælg kommune</option>
								<?php 
									foreach($districts as $district){
										echo "<option value='".$district['district_id']."'>".$district['district_name']."</option>"; 
									}            
								?>
							</select>
						</div>
						<?php } ?>
						<div class="filter-option">
							<p>Uddannelse</p>
							<select class="form-control dropdown py-2 px-4" name="qualification-id" id="filter-qualification-id">
								<option value="">Vælg uddannelse</option>
								<?php 
									foreach($qualifications as $qualification){
										echo "<option value='".$qualification['qualification_id']."'>".$qualification['qualification_name']."</option>";
									}            
								?>
							</select>
						</div>
						<div class="filter-option">
							<p>Stilling</p>
							<select class="form-control dropdown py-2 px-4" name="shift-id" id="filter-shift-id" >
								<option value="">Vælg stilling</option>
								<?php 
									foreach($shifts as $shift){
										echo "<option value='".$shift['shift_id']."'>".$shift['shift_name']."</option>";
									}            
								?>
							</select>
						</div>
						<br/>
						<div class="filter-option">
							<p>Fra dato</p>
							<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control py-2 px-4 date-picker" id="filter-from-date" name="from-date" placeholder="Fra dato" data-actual-date="">
						</div>
						<div class="filter-option">
							<p>Til dato</p>
							<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control py-2 px-4 date-picker" id="filter-to-date" name="to-date" placeholder="Til dato" data-actual-date="">
						</div>
						<div class="filter-option">
							<p>Fra starttid</p>							
							<select class="form-control dropdown py-2 px-4" name="from-time-id" id="filter-from-time-id" >
								<option value="">Fra starttid</option>
								<?php 
									foreach($times as $time){
										echo "<option value='".$time['time_id']."'>".$time['time_value']."</option>"; 
									}
								?>
							</select>
						</div>
						<div class="filter-option">
							<p>Til starttid</p>
							<select class="form-control dropdown py-2 px-4" name="to-time-id" id="filter-to-time-id" >
								<option value="">Til starttid</option>
								<?php 
									foreach($times as $time){
										echo "<option value='".$time['time_id']."'>".$time['time_value']."</option>"; 
									}
								?>
							</select>
						</div>
						<br/>

						<button id="search" class="save" onclick="filterBookings()">Søg</button>
						<button id="search" class="cancel" onclick="resetBookingsFilter()">Nulstil</button>
					</div>
					<div class="accordion-custom d-flex flex-column gap-2" id="bookings-container" data-active-page=""></div>
					<div class="pagination" id="pagination"></div>
				<?php
				}
				if ($user_permission == 3) { ?>
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