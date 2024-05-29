
<div class="row">
<div class="col-12 col-sm-8 offeset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
		<h2 class="text-center">Hej <?=$ruser->data()['fname'];?>,</h2>
		<p class="text-center">Nulstil venligst din adgangskode herunder:</p>
		<form action="" method="post">
			<?php if(!$errors=='') { display_errors($errors); } ?>
			<div class="form-group">
				<label for="password">Ny adgangskode:</label>
				<input type="password" name="password" value="" id="password" class="form-control" autocomplete="new-password">
			</div>
			<div class="form-group">
				<label for="confirm">Bekræft ny adgangskode:</label>
				<input type="password" name="confirm" value="" id="confirm" class="form-control" autocomplete='new-password'>
			</div>
			<input type="hidden" name="csrf" value="<?=Token::generate();?>">
			<input type="hidden" name="email" value="<?=$email;?>">
			<input type="hidden" name="vericode" value="<?=$vericode;?>">
			<input type="submit" name="resetPassword" value="Nulstil" class="btn btn-primary">
		</form>
		<br />
	</div><!-- /.col -->
</div><!-- /.row -->
