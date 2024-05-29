
<div class="row">
<div class="col-12 col-sm-8 offeset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
		<h2>Nulstil adgangskoden</h2>
		<!-- <ol>
			<li>Indtast din e-mailadresse, og klik på Nulstil</li>
			<li>Tjek din e-mail, og klik på det link, der er sendt til dig.</li>
			<li>Følg vejledningen på skærmen</li>
		</ol> -->
		<?php if(!$errors=='') { display_errors($errors); } ?>
		<form action="" method="post" class="form " id="pwReset">

			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" name="email" placeholder="Email" class="form-control" autofocus autocomplete='email'>
			</div>

			<input type="hidden" name="csrf" value="<?=Token::generate();?>">
			<div class="form-actions">
				<input type="submit" name="forgotten_password" value="Nulstil" class="save w-100p no-margin mb-2">
			</div>
		</form>
	</div>
</div>
