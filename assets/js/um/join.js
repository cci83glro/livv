$(document).ready(function() {

  $('#password_view_control').hover(function () {
    $('#password').attr('type', 'text');
    $('#confirm').attr('type', 'text');
  }, function () {
      $('#password').attr('type', 'password');
      $('#confirm').attr('type', 'password');
  });

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