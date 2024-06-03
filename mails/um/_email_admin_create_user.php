<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
  <p>Kære {{first_name}} {{last_name}},</p>
  <p>Velkommen til {{site_name}}! En administrator har lige oprettet en brugerkonto til dig. </p>
  <p><label>Brugernavn:</label> {{email}}</p>
  <p>Før du kan logge på skal du nulstille din adgangskode ved at klikke <a href="{{reset_password_url}}">her</a>.</p>
  <p>Bemærk venligst, at linket kun er gyldig i {{join_vericode_expiry}} timer.</p>
  <br/>
  <p>Venlig hilsen,</p>
  <p>Liv Vikar holdet</p>
</body>
</html>
