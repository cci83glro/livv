<?php
	$public = true;
	include_once "header.php";
?>

<main>
	<!-- Banner -->
	<section class="section image-infinite-bg position-relative"
		style="background-size: cover; background-position: center ;padding: 10em 1em 10em 1em;"
		data-images='["assets/images/index-page-banner-1.webp" , "assets/images/index-page-banner-2.webp"]'>
		<div class="r-container h-100">
			<div class="image-overlay"></div>
			<div class="d-flex flex-column justify-content-center gap-3 h-100 position-relative"
				style="max-width: 768px; z-index: 2;">
				<h1 class="text-title text-white fw-bold font-1 lh-1">Liv Vikar</h1>
				<h6 class="text-white uppercase mb-15">Liv der redes skal leves</h6>
				<div>
					<a type="button" href="about_us.html"
						class="btn bg-primary-color secondary-color">Om os</a>
				</div>
			</div>
		</div>
	</section>

	<section class="position-relative" style="margin-top: -60px;">
		<div class="r-container px-lg-0 px-4">
			<div class="overflow-hidden rounded-4">
				<div class="row row-cols-1 row-cols-lg-3">

					<div class="col bg-secondary-color text-white">
						<div class="d-flex flex-row gap-2 align-items-center  p-4">
							<div class="rounded-circle bg-primary-color icon-box">
								<i class="fa-solid fa-user-doctor secondary-color"></i>
							</div>
							<div class="d-flex flex-column">								
								<span class="fw-bold font-1 fs-5 lh-1">
									Erfaren / uddannet personale
								</span>
							</div>
						</div>
					</div>
					<div class="col bg-primary-color text-black border-1-secondary-color">
						<div class="d-flex flex-row gap-2 align-items-center  p-4">
							<div class="rounded-circle bg-secondary-color icon-box">
								<i class="fa-solid fa-hand-holding-heart primary-color"></i>
							</div>
							<div class="d-flex flex-column">								
								<span class="fw-bold font-1 fs-5 lh-1 secondary-color">
									Personlig hjælp
								</span>
							</div>
						</div>
					</div>
					<div class="col bg-secondary-color text-white">
						<div class="d-flex flex-row gap-2 align-items-center  p-4">
							<div class="rounded-circle bg-primary-color icon-box">
								<i class="fa-solid fa-star secondary-color"></i>
							</div>
							<div class="d-flex flex-column">
								<span class="fw-bold font-1 fs-5 lh-1">
									Høj kvalitets ydelser
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="r-container">
			<h6 class="font-2 accent-color">Hvem er vi?</h6>
			<h3 class="font-1 lh-1 fw-bold fs-1 mb-3">Læs lidt om os herunder</h3>
			<div class="d-flex  gap-2 text-center">
				
				<!-- <p class="text-gray mx-auto" style="max-width: 768px;">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sed pharetra erat. Integer
					ullamcorper quis est in vehicula. Sed eu cursus dui. Aenean vel velit non neque dictum interdum
					a nec ex.
				</p> -->
				<div class="accordion d-inline-flex flex-column gap-2 text-start col-lg-6" id="accordionAboutUs">
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
								data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								Hvem er vores kollegaer?
							</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse show"
							data-bs-parent="#accordionAboutUs">
							<div class="accordion-body">
								Liv vikar er en arbejdsplads for sociale- og sundhedshjælpere, social- og sundhedsassistenter, sygeplejersker, forflytningsvejledere og vi er også åbne for elever.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button fw-bold collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
								aria-controls="collapseTwo">
								Hvad fokuserer vi mest på?
							</button>
						</h2>
						<div id="collapseTwo" class="accordion-collapse collapse"
							data-bs-parent="#accordionAboutUs">
							<div class="accordion-body">
								Vi har stort fokus på borgere, som har brug for støtte og hjælp i dagligdagen.
								Vi leverer hjemmepleje af høj kvalitet og arbejder udefra et værdisæt, der sætter rammerne for, hvad vi gør og siger.
								Det er vigtigt for os at betragte alle som hele mennesker. Derfor hjælper vi dig med at bevare din personlighed og livskvalitet.
								Ydermere er vi stolte af at sige, at vi ikke kører på tid, men på ydelser.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button fw-bold collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
								aria-controls="collapseThree">
								Hvad tilbyder vi?
							</button>
						</h2>
						<div id="collapseThree" class="accordion-collapse collapse"
							data-bs-parent="#accordionAboutUs">
							<div class="accordion-body">
								Hos Liv vikar  privat hjemmepleje leverer vi hjælp døgnet rundt.
								Vi tilbyder både praktisk hjælp og hjælp til personlig pleje.
								Liv Vikar er et stærkt team, som arbejder tæt sammen og har altid hjertet med på det rette sted.
								Vores team er bygget på høj faglighed og vi stræber altid efter, at du og dine kære oplever tryghed, respekt for dig og dit liv og omsorg.
							</div>
						</div>
					</div>
				</div>
				<div class="col text-white gap-3 col-lg-6 bg-primary-color text-center justify-content-center align-items-center services-container">
					<div class="rounded-circle p-1 bg-secondary-color primary-color center mb-15" style="width: 4rem; height: 4rem; font-size: 2.3rem;">
						<i class="fa-solid fa-clipboard"></i>
					</div>
					<h4 class="font-1 lh-1 fw-bold secondary-color mb-15">Vores ydelser</h4>
					<p class="font-1 lh-1 fw-bold service">Personlig hjælp: støtte i livskvalitet</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp til vask/bad, toiletbesøg, af- og påklædning</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp til at tage medicin</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp i forbindelse med anretning/servering af mad</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp til at komme i og op af sengen/ kørestol</p>
					<p class="font-1 lh-1 fw-bold service">Praktisk hjælp</p>
					<p class="font-1 lh-1 fw-bold service">Rengøring</p>
					<p class="font-1 lh-1 fw-bold service">Støvsugning / gulvvask</p>
					<p class="font-1 lh-1 fw-bold service">Tøjvask</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="r-container">
			<div class="row row-cols-1 row-cols-lg-2">
				<div class="col mb-3 pe-lg-3 position-relative">
					<div class="overlay right"></div>
					<div class="position-relative pe-5">
						<img src="assets/images/hands-with-values.webp" alt="" class="img-fluid" style="z-index: -2;">
					</div>
				</div>
				<div class="col mb-3">
					<div class="d-flex flex-column gap-3 p-3">
						<h6 class="accent-color font-2 ">Erfaring og kvalifikationer</h6>
						<h3 class="text-black font-1 lh-1 fw-semibold mb-15">Dette definerer os som firma
						</h3>
						<p class=" mb-0">
							Alle vores ansatte har og får løbende udfyldt kompetenceskemaer.
						</p>
						<p class=" mb-0">
							Vi prioriterer minimum 3 års erfaring ved ansættelser.
						</p>
						<p class=" mb-15">
							Ansvarshavende sygeplejersker skal have minimum 2 års erfaring ude i den akute medicinske regi.
						</p>
						<div class="row row-cols-2">
							<div class="col mb-1">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Sygeplejersker</span>
								</div>
							</div>
							<div class="col mb-1">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Social og sundhedsassistenter</span>
								</div>
							</div>
							<div class="col mb-1">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Social og sundheds hjælpere
									</span>
								</div>
							</div>
							<div class="col mb-1">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Pædagoger</span>
								</div>
							</div>
							<div class="col mb-1">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Ergoterapeuter</span>
								</div>
							</div>
						</div>
						<div class="my-4 border-bottom w-100"></div>
						<div class="row row-cols-1 row-cols-lg-2">
							<div class="col mb-3">
								<div
									class="d-flex flex-lg-row flex-column justify-content-lg-start align-items-center gap-2 justify-content-center">
									<div class="bg-accent-color text-white rounded-3 icon-box"
										style="font-size: 20px; width: 3rem; height: 3rem;">
										<i class="fa-solid fa-user-nurse"></i>
									</div>
									<span class="font-1 fs-5 lh-1 fw-semibold">Vi er eksperter på vores område</span>
								</div>
							</div>
							<div class="col mb-3">
								<div
									class="d-flex flex-lg-row flex-column justify-content-lg-start align-items-center gap-2 justify-content-center">
									<div class="bg-accent-color text-white rounded-3 icon-box"
										style="font-size: 20px; width: 3rem; height: 3rem;">
										<i class="fa-solid fa-house-medical-circle-check"></i>
									</div>
									<span class="font-1 fs-5 lh-1 fw-semibold">Vi tilbyder sikkerhed og tillid</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Why Choose Seniorsy -->
	<section class="section">
		<div class="r-container">
			<div class="d-flex flex-lg-row flex-column-reverse">
				<div class="col mb-3">
					<div class="d-flex flex-column gap-3 p-3 h-100 justify-content-center">
						<h6 class="accent-color font-2 ">Sådan vælger du os</h6>
						<h3 class="text-black font-1 lh-1 fw-bold">Fritvalgsordning er til alle
						</h3>
						<div class="d-flex flex-lg-row flex-column">							
							<div class="col col-lg-12">
								<div class="d-flex flex-column ps-3 mb-15">
									<p>
										Formålet med fritvalgsordning er at adskille “begrænsninger” fra problemstillinde løsninger.
										Det gør det lettere får borgeren at få dækket aktuelle behov samt støtte end livskvalitet.
										Med en privat hjemmepleje leverandør har du mulighed til at vælge Liv Vikar uden selv at skulle betale.
									</p>
									<p>
										Du skal bare følge disse trin til at vælge Liv Vikar:
									</p>
									<div class="d-flex flex-column gap-2">
										<div class="d-flex flex-row gap-2 align-items-center">
											<i class="fa-solid fa-square-check accent-color"></i>
											Kontakt din kommune, ansøg om hjemmehjælp til personlig pleje og vælg Liv Vikar som leverandør
										</div>
										<div class="d-flex flex-row gap-2 align-items-center">
											<i class="fa-solid fa-square-check accent-color"></i>
											Når ansøgningen er godkendt og visitationen har foretaget deres vurdering, vil Liv Vikar besøge dig. Sammen tilrettelægger vi den løsning, der bedst passer til dine ønsker og behov
										</div>
									</div>
								</div>
								<div class="col text-white gap-3 rounded-2 bg-accent-color p-3 text-center d-flex flex-column justify-content-center align-items-center">
								<h5 class="font-1 lh-1 fw-bold mb-0">Har du brug for hjælp? <a class="primary-color underlined" href="kontakt.php">Kontakt os</a></h5>								
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col mb-5 pe-lg-5 position-relative">
					<div class="overlay left mt-2"></div>
					<div class="position-relative ps-5">
						<img src="assets/images/hvordan-vaelge-os.webp" alt="" class="img-fluid rounded-3"
							style="z-index: -2;">						
					</div>
				</div>
			</div>
		</div>
	</section>

</main>

<?php include_once "footer.php"?>

</body>
</html>