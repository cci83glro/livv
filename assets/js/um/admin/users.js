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
});