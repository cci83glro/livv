
<?php
	session_start();
	include_once __DIR__."/helpers/generic-helpers.php";

	if(array_key_exists('email', $_POST) && !IsNullOrEmptyString($_POST['email'])) {
		
		//include_once __DIR__."/helpers/classes/Config.php";
		//include_once __DIR__."/helpers/classes/Session.php";
		//include_once __DIR__."/helpers/classes/Token.php";
		include_once __DIR__."/helpers/email-helpers.php";
	
		if (array_key_exists('title', $_POST) && !IsNullOrEmptyString($_POST['title'])) {
			exit();
		}
		
		$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Invalid CSRF token.");
		}
		// $token = $_POST['csrf'];
		// if (!Token::check($token)) {
		// 	$response = [
		// 		"status" => false,
		// 		"message" => 'Der er sket en session fejl.'
		// 	];
		// 	echo json_encode($response);
		// 	exit();
		// } 
		
		$body = get_email_body('_email_contact_form.php');
		$body = str_replace("{{name}}", $_POST['name'], $body);
		$body = str_replace("{{email}}", $_POST['email'], $body);
		$body = str_replace("{{phone}}", $_POST['phone'], $body);
		$body = str_replace("{{subject}}", $_POST['subject'], $body);
		$body = str_replace("{{message}}", $_POST['message'], $body);
		$result = send_email('kontakt@livvikar.dk', 'Ny besked via kontaktformularen', $body);
	
		if ($result=='ok') {
			$response = [
				"status" => true,
				"message" => 'Tak for din besked!<br/>Vi vender tilbage hurtigst muligt.'
			];
			unset($_SESSION['csrf_token']);
		} else if ($result=='error') {
			$response = [
				"status" => false,
				"message" => 'Der er sket en fejl.'
			];
		}		
		
		if ($isAjax) {
			header('Content-type:application/json;charset=utf-8');
			echo json_encode($response);
			exit();
		}
	}

	$public = true;
	$pageTitle = 'Kontakt';
	include_once __DIR__."/master-pages/header.php";

?>

<main>
	<section class="section page-banner position-relative" style="background-image: url(assets/images/contact-page-banner.webp);">
		<div class="r-container">
			<div class="image-overlay"></div>
			<div class="page-title position-relative" style="z-index: 2;">
				<h1 class="font-1 fw-bold text-white">Kontakt</h1>                    
			</div>
		</div>
	</section>

	<section class="section">
		<div class="r-container">
			<div class="row row-cols-1 row-cols-lg-2">
				<div class="col mb-3">
					<div class="d-flex flex-column gap-3 h-100 justify-content-center">
						<h6 class="font-2 accent-color">Kontakt os</h6>
						<h3 class="font-1 lh-1 fw-bold fs-1">Vi glæder os til at høre fra dig</h3>
						<p>Du kan komme i kontakt med os enten via telefon, email, kontaktformularen her på siden eller bare dukke fysisk op på vores kontor. Ligemeget hvad du vælger så glæder vi os til at snakke med dig!</p>
						<div class="d-flex flex-column opening-hours mb-4">
							<div class="py-1 border-bottom">
								<h6 class="font-1 fw-bold">Åbningstider</h6>
							</div>
							<div class="py-2 d-flex flex-row gap-5">
								<span class="days">Mandag - Torsdag</span>
								<span class="d-flex align-items-center gap-2">
									<i class="fa-regular fa-clock accent-color"></i>
									08:00 - 16:00
								</span>
							</div>
							<div class="py-2 border-bottom d-flex flex-row gap-5">
								<span class="days">Fredag</span>
								<span class="d-flex align-items-center gap-2">
									<i class="fa-regular fa-clock accent-color"></i>
									08:00 - 15:00
								</span>
							</div>
						</div>
						<div class="d-flex flex-column gap-3">
							<div class="d-flex flex-row gap-3 align-items-center">
								<div class="bg-accent-color rounded-3 p-2 text-white" style="aspect-ratio: 1/1;">
									<h4 class="lh-1 m-0 p-0 fw-bold font-1">
										<i class="fa-solid fa-map-location-dot"></i>
									</h4>
								</div>
								<div class="d-flex flex-column">
									<h6 class="font-1 fw-bold lh-1 m-0 text-gray">
										Besøg os
									</h6>
									<span class="fs-4 fw-bold font-1">Theilgaards Alle 7, 4600 Køge</span>
								</div>
							</div>
							<div class="d-flex flex-row gap-3 align-items-center">
								<div class="bg-accent-color rounded-3 p-2 text-white" style="aspect-ratio: 1/1;">
									<h4 class="lh-1 m-0 p-0 fw-bold font-1">
										<i class="fa-solid fa-envelope"></i>
									</h4>
								</div>
								<div class="d-flex flex-column">
									<h6 class="font-1 fw-bold lh-1 m-0 text-gray">
										Skriv til os
									</h6>
									<span class="fs-4 fw-bold font-1">kontakt@livvikar.dk</span>
								</div>
							</div>
							<div class="d-flex flex-row gap-3 align-items-center">
								<div class="bg-accent-color rounded-3 p-2 text-white" style="aspect-ratio: 1/1;">
									<h4 class="lh-1 m-0 p-0 fw-bold font-1">
										<i class="fa-solid fa-phone"></i>
									</h4>
								</div>
								<div class="d-flex flex-column">
									<h6 class="font-1 fw-bold lh-1 m-0 text-gray">
										Ring til os
									</h6>
									<span class="fs-4 fw-bold font-1">7373 8800</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col mb-3">
					<div class="bg-secondary-color rounded-4">
						<div class="h-100 d-flex flex-column p-5">
							<h3 class="font-1 lh-1 fw-bold fs-1 mb-3 text-white">Skriv til os</h3>
							
							<form id="contact-form" action="contact.php" method="POST"
								class="flex-column h-100 justify-content-center w-100 needs-validation mb-3 form"
								novalidate>
								<div class="mb-3 title-wrapper">
									<input type="text" class="form-control py-2 px-4" name="title" id="title"
										placeholder="Title">
									<div class="invalid-feedback">
										Ugyldig værdi.
									</div>
								</div>
								<div class="mb-3 name-wrapper">
									<input type="text" class="form-control py-2 px-4" name="name" id="name"
										placeholder="Navn" required>
									<div class="invalid-feedback">
										Feltet skal udfyldes.
									</div>
								</div>
								<div class="mb-3 email-wrapper">
									<input type="email" class="form-control py-2 px-4" name="email" id="email"
										placeholder="Email" required>
									<div class="invalid-feedback">
										Feltet skal udfyldes.
									</div>
								</div>
								<div class="mb-3 phone-wrapper">
									<input type="phone" class="form-control py-2 px-4" name="phone" id="phone"
										placeholder="Telefon" required>
									<div class="invalid-feedback">
										Feltet skal udfyldes.
									</div>
								</div>
								<div class="mb-3 subject-wrapper">
									<input type="text" class="form-control py-2 px-4" name="subject" id="subject"
										placeholder="Subjekt" required>
									<div class="invalid-feedback">
										Feltet skal udfyldes.
									</div>
								</div>
								<div class="mb-3 message-wrapper">
									<textarea class="form-control py-2 px-4" id="message" name="message" rows="5"
										placeholder="Besked"></textarea>
								</div>
								<?php
									if (empty($_SESSION['csrf_token'])) {
										$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
									}
								?>
								<input type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>" name="csrf_token">
								<div class="form-actions">
									<button id="contact-send-button" type="submit" class="save submit_form w-50p">
										Send
									</button>
								</div>
							</form>

							<div id="success-state" class="success_msg toast align-items-center w-100 shadow-none mb-3 border border-success rounded-0 my-4"
								role="alert" aria-live="assertive" aria-atomic="true">
								<div class="d-flex p-2">
									<div
										class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-success">
										<i class="fa-solid fa-check f-36 text-success"></i>
										Din besked er blevet sendt.
									</div>
								</div>
							</div>
							<div id="error-state" class="toast align-items-center w-100 shadow-none border-danger mb-3 my-4 border rounded-0"
								role="alert" aria-live="assertive" aria-atomic="true">
								<div class="d-flex p-2">
									<div
										class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-danger">
										<i class="fa-solid fa-triangle-exclamation f-36 text-danger"></i>
										Det skete en fejl.
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="pb-5">
		<div class="r-container">
			<div class="mb-3">
				<iframe loading="lazy" class="maps overflow-hidden rounded-3"
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2261.118080796165!2d12.186365677073088!3d55.47803971312057!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4652f11dce35b169%3A0xc688f16a3e9a11a5!2sTheilgaards%20Alle%207%2C%204600%20K%C3%B8ge!5e0!3m2!1sen!2sdk!4v1717016498564!5m2!1sen!2sdk"
					title="Theilgaards Alle 7, 4600 Køge"
					aria-label="Theilgaards Alle 7, 4600 Køge, Danmark"></iframe>
			</div>
		</div>
	</section>

</main>

<?php include_once __DIR__."/master-pages/footer.php"?>
<!-- <script src="assets/js/contact.js"></script> -->

</body>
</html>