<?php
//	session_start();

	$public = true;
	$pageTitle = "Vikarbureau";
	$canonical = "https://livvikar.dk";
	$description = "Liv Vikar udgør en arbejdsplads for fagpersoner indenfor sundhedssektoren, herunder social- og sundhedshjælpere, social- og sundhedsassistenter, sygeplejersker, pædagoger, ergoterapeuter og forflytningsvejledere";
	include_once __DIR__."/master-pages/header.php";
?>

<main>
	<!-- Banner -->
	<section class="section image-infinite-bg position-relative"
		style="background-size: cover; background-position: center ;padding: 10em 1em 10em 1em;"
		data-mobile-images='["<?=$us_url_root?>assets/images/index-page-banner-mobile-1.webp" , "<?=$us_url_root?>assets/images/index-page-banner-mobile-2.webp"]'
		data-desktop-images='["<?=$us_url_root?>assets/images/index-page-banner-1.webp" , "<?=$us_url_root?>assets/images/index-page-banner-2.webp"]'>
		<div class="r-container h-100">
			<div class="image-overlay"></div>
			<div class="d-flex flex-column justify-content-center gap-3 h-100 position-relative"
				style="max-width: 768px; z-index: 2;">
				<h1 class="text-title text-white fw-bold font-1 lh-1">Liv Vikar</h1>
				<h6 class="text-white uppercase mb-15">Liv der redes, skal leves</h6>
				<div class="form-actions align-left">
					<div class="buttons-wrapper">
						<a type="button" href="#about-us-section" class="save no-margin">Om os</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script>
        function updateBackgroundImages() {
            const section = document.querySelector('.image-infinite-bg');
            const desktopImages = section.getAttribute('data-desktop-images');
            const mobileImages = section.getAttribute('data-mobile-images');
            
            const imagesToUse = window.innerWidth <= 768 ? mobileImages : desktopImages;
            section.setAttribute('data-images', imagesToUse);
        }

        updateBackgroundImages();
        window.addEventListener('resize', updateBackgroundImages);
    </script>

	<section class="position-relative" style="margin-top: -60px;">
		<div class="r-container px-lg-0 px-4">
			<div class="overflow-hidden rounded-4">
				<div class="row row-cols-1 row-cols-lg-3">

					<div class="col bg-secondary-color company-highlight">
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
					<div class="col bg-primary-color border-1-secondary-color company-highlight">
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
					<div class="col bg-secondary-color company-highlight">
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

	<section class="section" id="services-section">
		<div class="r-container">
			<h6 class="font-2 accent-color">Hvem er vi?</h6>
			<h3 class="font-1 lh-1 fw-bold fs-1 mb-3">Kort om os</h3>
			<div class="services-wrapper">	
				<div class="accordion text-start services-group" id="accordionAboutUs">
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
								Liv Vikar udgør en arbejdsplads for fagpersoner indenfor sundhedssektoren, herunder social- og sundhedshjælpere, social- og sundhedsassistenter, sygeplejersker, pædagoger, ergoterapeuter og forflytningsvejledere. Vi er ligeledes åben for elever under uddannelse.
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
								Vi prioriterer højt borgere, der kræver støtte og assistance i deres daglige liv. Vores tilbud om hjemmepleje er af høj kvalitet og styres af et værdisæt, der definerer vores handlinger og kommunikation. Det er essentielt for os at anerkende hver enkelt person som et helt menneske. Derfor er det vores mål at bevare og understøtte individets personlighed og livskvalitet. Desuden er vi stolte over at arbejde baseret på kvaliteten af de ydelser, vi leverer, fremfor den tid, det tager at udføre dem.
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
								Hos Liv Vikar Privat Hjemmepleje tilbyder vi hjælp døgnet rundt, herunder både praktisk hjælp og hjælp til personlig pleje. Liv Vikar består af et dedikeret team, der samarbejder tæt og altid handler med empati og engagement. Vores team er bygget på høj faglighed, og vi er konstant engagerede i at sikre, at både du og dine nærmeste oplever tryghed, respekt for den enkeltes livssituation, og omsorg.
							</div>
						</div>
					</div>
				</div>
				<div class="col text-white services-group text-center services-container">
					<div class="subsection-title-wrapper">
						<div class="rounded-circle p-1 bg-secondary-color primary-color center" style="width: 4rem; height: 4rem; font-size: 2.3rem;">
							<i class="fa-solid fa-clipboard"></i>						
						</div>
						<h2 class="font-1 lh-1 fw-bold secondary-color">Vores ydelser</h2>
					</div>					
					<h3>Personlig hjælp / Støtte i livskvalitet</h3>					
					<p class="font-1 lh-1 fw-bold service">Hjælp til vask/bad, toiletbesøg, af- og påklædning</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp til at tage medicin</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp i forbindelse med anretning/servering af mad</p>
					<p class="font-1 lh-1 fw-bold service">Hjælp til at komme i og op af sengen/ kørestol</p>
					<h3>Praktisk hjælp</h3>
					<p class="font-1 lh-1 fw-bold service">Rengøring</p>
					<p class="font-1 lh-1 fw-bold service">Støvsugning / gulvvask</p>
					<p class="font-1 lh-1 fw-bold service">Tøjvask</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section" id="courses-section">
		<div class="r-container">
			<h6 class="font-2 accent-color">Kurser</h6>
			<div class="d-flex  gap-2 text-center">				
				<div class="col text-white gap-3 bg-primary-color text-center justify-content-center align-items-center courses-container">
					<h2 class="font-1 lh-1 fw-bold secondary-color">Vi tilbyder også følgende kurser</h2>
					<p class="font-1 lh-1 fw-bold course">Førstehjælp</p>
					<p class="font-1 lh-1 fw-bold course">Medicinhåndtering</p>
					<p class="font-1 lh-1 fw-bold course">Supervision (arbejdsmiljø)</p>
					<p class="font-1 lh-1 fw-bold course">Palliativ pleje</p>
					<p class="font-1 lh-1 fw-bold course">Terminal pleje</p>
					<p class="font-1 lh-1 fw-bold course">Demens kursus</p>
					<p class="font-1 lh-1 fw-bold course">Kompetence skema</p>
					<p class="font-1 lh-1 fw-bold course">Forflytningskursus</p>
				</div>
			</div>
		</div>
	</section>

	<section class="section" id="about-us-section">
		<div class="r-container">
			<div class="row row-cols-1 row-cols-lg-2">
				<div class="col values-image pe-lg-3 position-relative">
					<img src="<?=$us_url_root?>assets/images/hands-with-values.webp" alt="Billede med værdier" class="img-fluid" style="z-index: -2;">
				</div>
				<div class="col">
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
							Ansvarshavende sygeplejersker skal have minimum 2 års erfaring ude i den akutte medicinske regi.
						</p>
						<div class="education-wrapper">
							<div class="col education-element">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Sygeplejersker</span>
								</div>
							</div>
							<div class="col education-element">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Social og sundhedsassistenter</span>
								</div>
							</div>
							<div class="col education-element">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Social og sundheds hjælpere
									</span>
								</div>
							</div>
							<div class="col education-element">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Pædagoger</span>
								</div>
							</div>
							<div class="col education-element">
								<div class="d-flex flex-row gap-2 align-items-center">
									<i class="fa-solid fa-circle-arrow-right accent-color"></i>
									<span >Ergoterapeuter</span>
								</div>
							</div>
						</div>
						<div class="border-bottom w-100"></div>
						<div class="row row-cols-1 row-cols-lg-2 about-us ">
							<div class="highlight-wrapper col mb-3">
								<div class="highlight justify-content-lg-start align-items-center gap-2 ">
									<div class="bg-accent-color text-white rounded-3 icon-box">
										<i class="fa-solid fa-user-nurse"></i>
									</div>
									<span class="font-1 fs-5 lh-1 fw-semibold">Vi er eksperter inden for vores område</span>
								</div>
							</div>
							<div class="highlight-wrapper col mb-3">
								<div class="highlight justify-content-lg-start align-items-center gap-2 ">
									<div class="bg-accent-color text-white rounded-3 icon-box">
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

	<section class="section" id="how-to-choose-us-section">
		<div class="r-container">
			<div class="d-flex flex-lg-row flex-column-reverse">
				<div class="col mb-3">
					<div class="d-flex flex-column gap-3 p-3 h-100 justify-content-center">
						<h6 class="accent-color font-2 ">Sådan vælger du os</h6>
						<h3 class="text-black font-1 lh-1 fw-bold">Fritvalgsordning er til alle
						</h3>
						<div class="d-flex flex-lg-row flex-column">							
							<div class="col col-lg-12">
								<div class="d-flex flex-column mb-15">
									<p>
										Formålet med fritvalgsordningen er at fjerne 'begrænsninger' fra problemløsende tiltag. Dette gør det lettere for borgeren at få dækket aktuelle behov og styrker livskvaliteten. Med en privat hjemmeplejeleverandør har du muligheden for at vælge Liv Vikar uden selv at skulle betale.
									</p>
									<p>
										Du skal blot følge disse simple trin for at vælge Liv Vikar:
									</p>
									<div class="d-flex flex-column gap-2">
										<div class="d-flex flex-row gap-2 align-items-center">
											- Kontakt din kommune
										</div>
										<div class="d-flex flex-row gap-2 align-items-center">
											- Ansøg om hjemmehjælp til personlig pleje
										</div>
										<div class="d-flex flex-row gap-2 align-items-center">
											- Vælg Liv Vikar som leverandør
										</div>
										<div class="d-flex flex-row gap-2">
											<i class="fa-solid fa-square-check accent-color"></i>
											Når ansøgningen er godkendt og visitationen har foretaget deres vurdering, vil Liv Vikar besøge dig. Sammen tilrettelægger vi den løsning, der bedst passer til dine ønsker og behov
										</div>
									</div>
								</div>
								<div class="col text-white gap-3 rounded-2 bg-accent-color p-3 text-center d-flex flex-column justify-content-center align-items-center">
								<h5 class="font-1 lh-1 fw-bold mb-0"><a class="primary-color underlined" href="kontakt.php">Har du brug for hjælp? Kontakt os</a></h5>								
							</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col mb-5 position-relative">
					<div class="position-relative ps-5">
						<img src="<?=$us_url_root?>assets/images/how-to-choose-us.webp" alt="Vikar sammen med en gammel dame" class="img-fluid rounded-3"
							style="z-index: -2;">						
					</div>
				</div>
			</div>
		</div>
	</section>

</main>

<?php include_once __DIR__."/master-pages/footer.php"?>

</body>
</html>