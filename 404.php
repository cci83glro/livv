<?php
  	$pageTitle = "Siden findes ikke - LivVikar";
	$public = true;
	include_once __DIR__."/master-pages/master.php";
?>

<main>
	<section class="section position-relative"
		style="background-image: url(image/dummy-img-1920x900.jpg); height: 90vh;">
		<div class="image-overlay-3"></div>
		<div class="r-container h-100">
			<div class="w-100 h-100 d-flex justify-content-center align-items-center position-relative"
				style="z-index: 2;">
				<div class="row row-cols-1 row-cols-2">
					<div class="col font-1 fw-bold px-4 lh-1 text-end"
						style="font-size: 15rem; border-right: 1px solid var(--secondary-color);">
						404
					</div>
					<div class="col px-5 d-flex flex-column justify-content-center">
						<h3 class="font-1 fw-bold">Siden findes ikke</h3>
						<p style="max-width: 768px;">Den side, du leder efter, findes ikke i vores system.</p>
						<div class="form-actions">
							<a href="<?=$home_page_url;?>" type="button" class="save " type="submit">Hjem</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include_once __DIR__."/master-pages/footer.php"?>

</body>
</html>