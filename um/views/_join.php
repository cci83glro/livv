<div class="container">
  <div class="row justify-content-md-center alternate-background">
    <main class="col-12 col-md-10 col-lg-8">
 
      <h1 class="form-signin-heading mt-4 mb-3 alternate-background">Registrer ny bruger</h1>
      <form class="form-signup p-4 mb-5" action="" method="POST" id="payment-form">

        <div class="row mb-3">
          <label for="fname" id="fname-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Fornavn *</label>
          <div class="col-12 col-md-8">
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Fornavn" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $fname;
                      } ?>" required autofocus autocomplete="given-name">
          </div>
        </div>

        <div class="row mb-3">
          <label for="lname" id="lname-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Efternavn *</label>
          <div class="col-12 col-md-8">
            <input type="text" class="form-control" id="lname" name="lname" placeholder="Efternavn" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $lname;
                      } ?>" required autocomplete="family-name">
          </div>
        </div>


        <div class="row mb-3">
          <label for="phone" id="phone-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Telefon</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="phone" id="phone" placeholder="Telefon" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $phone;
                      } ?>" required autocomplete="phone">
          </div>
        </div>

        <div class="row mb-3">
          <label for="email" id="email-label" class="col-form-label col-12 col-md-4 text-md-right text-md-end">Email *</label>
          <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="email" id="email" placeholder="Email" 
                value="<?php if (!$form_valid && !empty($_POST)) {
                        echo $email;
                      } ?>" required autocomplete="email">
          </div>
        </div>

        <div class="row mb-3">
          <?php
          $character_range = "Min 5 max 50 chars";
          $character_statement = '<span id="character_range" class="text-muted">' . $character_range . ' </span>';
          $num_caps_statement = '<span id="caps" class="text-muted">Mindst 1 stort bogstav</span>';
          $num_numbers_statement = '<span id="number" class="text-muted">Mindst 1 tal</span>';
          $password_match_statement = '<span id="password_match" class="text-muted">Indtast 2 gange den samme adgangskode</span>';
          ?>

          <div class="col-12 col-sm-6 col-lg-5 col-xl-4">
            <!-- <p><strong>Krav til adgangskoden:</strong></p> -->
            <ul class="list-unstyled">
              <li>
                <span id="character_range_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $character_statement; ?>
              </li>
              <li>
                <span id="num_caps_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $num_caps_statement; ?>
              </li>
              <li>
                <span id="num_numbers_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $num_numbers_statement; ?>
              </li>
              <li>
                <span id="password_match_icon" class="fa fa-close text-muted" style="width: 16px;"></span>&nbsp;&nbsp;<?php echo $password_match_statement; ?>
              </li>
            </ul>

            <p><a class="nounderline secondary-color" id="password_view_control" style="cursor: pointer;"><span class="fa fa-eye"></span> Vis adgangskoderne</a></p>
          </div>
          <div class="col-12 col-sm-6 col-lg-7 col-xl-8">
            <div class="row mb-3">
              <label for="password" id="password-label" class="form-label col-12">Adgangskode *</label>
              <div class="col-12">
                <input class="form-control" type="password" name="password" id="password" placeholder="Adgangskode" required autocomplete="new-password" aria-describedby="passwordhelp">
              </div>
            </div>
            <div class="row mb-3">
              <label for="confirm" id="confirm-label" class="form-label col-12">Bekræft adgangskode *</label>
              <div class="col-12">
                <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Bekræft adgangskode" required autocomplete="new-password">
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" value="<?= Token::generate(); ?>" name="csrf">

        <div class="g-recaptcha" data-sitekey="6LeMldwpAAAAAHsHbEJecHb2jxkLkZ9X67IwpCCl"></div>

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

<script type="text/javascript">
  $(document).ready(function() {

    $("#password").keyup(function() {
      var pswd = $("#password").val();

      //validate the length
      if (pswd.length >= 5 && pswd.length <= 50) {
        ToggleClasses(true, $("#character_range_icon"), $("#character_range"));
      } else {
        ToggleClasses(false, $("#character_range_icon"), $("#character_range"));
      }

      //validate capital letter
      if (pswd.match(/[A-Z]/)) {
        ToggleClasses(true, $("#num_caps_icon"), $("#caps"));
      } else {
        ToggleClasses(false, $("#num_caps_icon"), $("#caps"));
      }

      //validate number
      if (pswd.match(/\d/)) {
        ToggleClasses(true, $("#num_numbers_icon"), $("#number"));
      } else {
        ToggleClasses(false, $("#num_numbers_icon"), $("#number"));
      }
    });

    $("#confirm").keyup(function() {
      var pswd = $("#password").val();
      var confirm_pswd = $("#confirm").val();

      //validate password_match
      if (pswd == confirm_pswd) {
        ToggleClasses(true, $("#password_match_icon"), $("#password_match"));
      } else {
        ToggleClasses(false, $("#password_match_icon"), $("#password_match"));
      }
    });

    function ToggleClasses(conditionMet, icon, text) {
      if (conditionMet) {
        icon.removeClass("text-muted").removeClass("fa-close").addClass("text-success").addClass("fa-check");
        text.removeClass("text-muted");
      } else {
        icon.removeClass("text-success").removeClass("fa-check").addClass("text-muted").addClass("fa-close");
        text.addClass("text-muted");
      }
    }
  });
</script>