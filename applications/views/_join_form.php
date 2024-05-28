<div class="container">
  <div class="row justify-content-md-center alternate-background">
    <main class="col-12 col-md-10 col-lg-8">
 
      <h1 class="form-signin-heading mt-4 mb-3 alternate-background">Ansøg som vikar</h1>
      <form class="form-signup p-4 mb-5" action="" method="POST" id="user-registration-form" onsubmit="return validateRecaptcha();">

        <div class="row mb-3 fname">
          <label for="fname" id="fname-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Fornavn *</label>
          <div class="col-12 col-md-8">
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Fornavn" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $fname;
                      } ?>" required autofocus autocomplete="given-name">
          </div>
        </div>

        <div class="row mb-3 lname">
          <label for="lname" id="lname-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Efternavn *</label>
          <div class="col-12 col-md-8">
            <input type="text" class="form-control" id="lname" name="lname" placeholder="Efternavn" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $lname;
                      } ?>" required autocomplete="family-name">
          </div>
        </div>

        <div class="row mb-3 sex">
          <label for="sex" id="sex-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Sex *</label>
          <div class="col-12 col-md-8">
            <input type="text" class="form-control" id="sex" name="sex" placeholder="Indtast kønnet (mand, kvinde, nonbinær ...)" 
                value="">
          </div>
        </div>

        <div class="row mb-3 phone">
          <label for="phone" id="phone-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Telefon *</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="phone" id="phone" placeholder="Telefon" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $phone;
                      } ?>" required autocomplete="phone">
          </div>
        </div>

        <div class="row mb-3 email">
          <label for="email" id="email-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Email *</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="email" id="email" placeholder="Email" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $email;
                      } ?>" required autocomplete="email">
          </div>
        </div>

        <div class="row mb-3 experience">
          <label for="experience" id="experience-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Uddannelse *</label>
          <div class="col-12 col-md-8">
            <select class="form-control dropdown py-2 px-4" id="qualification" name="qualification" required>
                <option value="">Vælg uddannelse</option>
                <?php 
                    foreach($qualifications as $qualification){
                        echo "<option value='".$qualification['qualification_id']."'>".$qualification['qualification_name']."</option>"; 
                    }            
                ?>
            </select>
            </div>
        </div>

        <div class="row mb-3 experience">
          <label for="experience" id="experience-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Antal års erfaring *</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="experience" id="experience" placeholder="Antal års erfaring" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $experience;
                      } ?>" required>
          </div>
        </div>

        <div class="row mb-3 namePhoneReference">
          <label for="namePhoneReference" id="namePhoneReference-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Navn og telefon for reference *</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="namePhoneReference" id="namePhoneReference" placeholder="Kontaktoplysninger for reference" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $namePhoneReference;
                      } ?>" required>
          </div>
        </div>

        <!-- <div class="row mb-3">
          <div class="g-recaptcha" data-sitekey=<?=$recaptchaKey?>></div>
        </div> -->

        <input type="hidden" value="<?= Token::generate(); ?>" name="csrf">

        <div class="row">
          <div class="form-actions">
            <button class="submit save" type="submit" id="next_button">
              <span class="fa fa-user-plus mr-2 me-2"></span> Registrer
            </button>
          </div>
        </div>
      </form>
    </main>
  </div>
</div>
