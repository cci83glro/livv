
<?php include_once "header.php"?>

<main>
        <!-- Banner -->
        <section class="section position-relative" style="background-image: url(assets/images/dummy-img-1920x900.jpg);">
            <div class="r-container">
                <div class="image-overlay"></div>
                <div class="position-relative" style="z-index: 2;">
                    <h1 class="font-1 fw-bold text-white">Bookings</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item fw-bold"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item fw-bold active" aria-current="page">Single Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="r-container">
                <div class="d-flex flex-lg-row flex-column-reverse gap-3">
                    
					<div class="d-flex flex-column gap-3">
						
						<div class="d-flex flex-column">
							<!-- <h3 class="font-1 fw-bold">Frequently Asked Questions</h3> -->
							<div class="accordion d-flex flex-column gap-2" id="accordionExample">
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
											data-bs-target="#collapseOne" aria-expanded="false"
											aria-controls="collapseOne">
											How To Change My Photo From Admin Dashboard ?
										</button>
									</h2>
									<div id="collapseOne" class="accordion-collapse collapse show"
										data-bs-parent="#accordionExample">
										<div class="accordion-body">
											Far far away, behind the word mountains, far from the countries Vokalia
											and Consonantia, there live the blind texts. Separated they live in
											Bookmarksgrove right at the coast
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button fw-bold collapsed" type="button"
											data-bs-toggle="collapse" data-bs-target="#collapseTwo"
											aria-expanded="false" aria-controls="collapseTwo">
											How To Change My Password Easily ?
										</button>
									</h2>
									<div id="collapseTwo" class="accordion-collapse collapse"
										data-bs-parent="#accordionExample">
										<div class="accordion-body">
											Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button fw-bold collapsed" type="button"
											data-bs-toggle="collapse" data-bs-target="#collapseThree"
											aria-expanded="false" aria-controls="collapseThree">
											How To Change My Subscription Plan Using PayPal
										</button>
									</h2>
									<div id="collapseThree" class="accordion-collapse collapse"
										data-bs-parent="#accordionExample">
										<div class="accordion-body">
											Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade bg-overlay" id="videomodal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-dark-color">
                        <iframe class="ifr-video" src="https://www.youtube.com/embed/FK2RaJ1EfA8?autoplay=1"
                            frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </section>

		<section class="section">
            <div class="r-container">
				<div class="bg-accent-color rounded-4">
					<div class="h-100 d-flex flex-column p-5">
						<h6 class="font-2 text-white">Fill The Form</h6>
						<h3 class="font-1 lh-1 fw-bold fs-1 mb-3 text-white">Get In Touch</h3>
						<div class="success_msg toast align-items-center w-100 shadow-none mb-3 border border-success rounded-0 my-4"
							role="alert" aria-live="assertive" aria-atomic="true">
							<div class="d-flex p-2">
								<div
									class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-success">
									<i class="fa-solid fa-check f-36 text-success"></i>
									Your Message Successfully Send.
								</div>
								<button type="button"
									class="me-2 m-auto bg-transparent border-0 ps-1 pe-0 text-success"
									data-bs-dismiss="toast" aria-label="Close"><i
										class="fa-solid fa-xmark"></i></button>
							</div>
						</div>
						<div class="error_msg toast align-items-center w-100 shadow-none border-danger mb-3 my-4 border rounded-0"
							role="alert" aria-live="assertive" aria-atomic="true">
							<div class="d-flex p-2">
								<div
									class="toast-body f-18 d-flex flex-row gap-3 align-items-center text-danger">
									<i class="fa-solid fa-triangle-exclamation f-36 text-danger"></i>
									Something Wrong ! Send Form Failed.
								</div>
								<button type="button"
									class="me-2 m-auto bg-transparent border-0 ps-1 pe-0 text-danger"
									data-bs-dismiss="toast" aria-label="Close"><i
										class="fa-solid fa-xmark"></i></button>
							</div>
						</div>
						<form action=""
							class="d-flex flex-column h-100 justify-content-center w-100 needs-validation mb-3 form"
							novalidate>
							<div class="mb-3">
								<input type="text" class="form-control py-2 px-4" name="name" id="name"
									placeholder="Name" required>
								<div class="invalid-feedback">
									The field is required.
								</div>
							</div>
							<div class="mb-3">
								<input type="email" class="form-control py-2 px-4" name="email" id="email"
									placeholder="Email" required>
								<div class="invalid-feedback">
									The field is required.
								</div>
							</div>
							<div class="mb-3">
								<input type="text" class="form-control py-2 px-4" name="subject" id="subject"
									placeholder="Subject" required>
								<div class="invalid-feedback">
									The field is required.
								</div>
							</div>
							<div class="mb-3">
								<textarea class="form-control py-2 px-4" id="message" name="message" rows="5"
									placeholder="Message"></textarea>
							</div>
							<div class="mb-3">
								<button type="submit" class="btn submit_form py-3">
									Send Message
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>

    </main>

<?php include_once "footer.php"?>