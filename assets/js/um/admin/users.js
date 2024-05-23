$(document).ready(function() {
  $('#userstable').DataTable({
    "pageLength": 25,
    "stateSave": true,
    "aLengthMenu": [
      [25, 50, 100, -1],
      [25, 50, 100, "All"]
    ],
    "aaSorting": []
  });

  $('.password_view_control').hover(function() {
    $('#password').attr('type', 'text');
    $('#confirm').attr('type', 'text');
  }, function() {
    $('#password').attr('type', 'password');
    $('#confirm').attr('type', 'password');
  });


  $('[data-toggle="popover"], .pwpopover').popover();
  $('.pwpopover').on('click', function(e) {
    $('.pwpopover').not(this).popover('hide');
  });
  $('.modal').on('hidden.bs.modal', function() {
    $('.pwpopover').popover('hide');
  });

  function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? null : decodeURIComponent(results[1].replace(/\+/g, ' '));
  }

  var paramName = getUrlParameter('create');
  if (paramName == 1) {
    $("#permission_id").val(3);
    $('#fname').val(getUrlParameter('fname'));
    $('#lname').val(getUrlParameter('lname'));
    $('#phone').val(getUrlParameter('phone'));
    $('#email').val(getUrlParameter('email'));
    $('#add-user-button').trigger('click');
  } 
});