<!DOCTYPE html>
<html lang="en">

<?php require_once 'master.php';?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheet -->
    <?php include_once "config/css.php"?>

    <style>
  /* Style for accordion */
  .accordion-item {
    border: 1px solid #ccc;
    margin-bottom: 10px;
  }
  .accordion-header {
    background-color: #f1f1f1;
    padding: 10px;
    cursor: pointer;
  }
  .accordion-content {
    display: none;
    padding: 10px;
  }
</style>

    <title>LivVikar - Home</title>
</head>

<body class="br-secondary-color">
    
    <!-- Header -->
    <section class="bg-accent-color-1 py-3">
        <div class="r-container">
            <div class="d-flex flex-lg-row flex-column justify-content-lg-between justify-content-center gap-3 banner">
                <ul class="d-flex m-0 flex-lg-row flex-column justify-content-center align-items-center gap-3 text-white font-2 text-center text-lg-start"
                    style="list-style: none;">
                    <li>RIng til os:&nbsp; <a href="tel:11223344">11 22 33 44</li>
                    <!-- <li>Email : hello@awesomesite.com</li>
                    <li>Opening Hours : 08:00am to 07:00Pm</li> -->
                </ul>
                <div class="social-container justify-content-center">
                    <a href="https://www.facebook.com" class="social-item">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://www.twitter.com" class="social-item">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="https://www.youtube.com" class="social-item">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="sticky-top bg-white">
        <div class="r-container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <div class="logo-container">
                            <img src="assets/images/logo.png" alt="" class="img-fluid">
                        </div>
                    </a>
                    <button class="navbar-toggler accent-color border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto font-2 fw-semibold gap-lg-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/">Ydelser</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/">Kurser</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="bookings.php">Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about_us.html">Om os</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.html">Kontakt</a>
                            </li>
                        </ul>
                        <a href="" type="button" class="btn button bg-secondary-color primary-color" type="submit">Opret vikar</a>
                    </div>
                </div>
            </nav>
        </div>
    </section>
    <!-- End  of Header -->