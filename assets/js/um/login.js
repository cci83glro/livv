$(document).ready(function() {
  $("#loginModal").modal({
    backdrop: 'static',
    keyboard: false
  })
  $("#loginModal").modal('show');
  setTimeout(function() {
    $('#username').focus();
  }, 500);

  const togglePassword = document.querySelector('#togglePassword');
  const togglePasswordIcon = document.querySelector('#togglePasswordIcon');
  const password = document.querySelector('#password');

  togglePassword.addEventListener('click', function(e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon

    if (type == "password") {
      togglePasswordIcon.classList.add('fa-eye');
      togglePasswordIcon.classList.remove('fa-eye-slash');
    } else {
      togglePasswordIcon.classList.add('fa-eye-slash');
      togglePasswordIcon.classList.remove('fa-eye');
    }

  });
});