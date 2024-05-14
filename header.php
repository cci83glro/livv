<!DOCTYPE html>
<html lang="en">

<?php
    require_once 'master.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheet -->
    <?php include_once "config/css.php"?>
    <title>LivVikar - Home</title>
    <?php if (isset($extra_head_html)) echo $extra_head_html;?>
</head>

<body class="br-secondary-color">
    
    <!-- Header -->
    <section class="bg-accent-color-1 py-3">
        <div class="r-container">
            <div class="d-flex flex-lg-row flex-column justify-content-lg-between justify-content-center gap-3 banner">
                <ul class="d-flex m-0 flex-lg-row flex-column justify-content-center align-items-center gap-3 text-white font-2 text-center text-lg-start"
                    style="list-style: none;">
                    <li><i class="fa-solid fa-location-dot"></i>&nbsp; <a class="location" target=_blank href="https://maps.app.goo.gl/aJnuGj3JGwNK5J5m9">Lidsøvej, 2730 Herlev</li>
                    <li><i class="fa-solid fa-phone"></i>&nbsp; <a class="phone" href="tel:11223344">11 22 33 44</li>
                    <li><i class="fa-solid fa-envelope"></i>&nbsp; <a class="email" href="mailto:kontakt@livvikar.dk">kontakt@livvikar.dk</li>
                    <!-- <li>Email : hello@awesomesite.com</li>
                    <li>Opening Hours : 08:00am to 07:00Pm</li> -->
                </ul>
                <div class="social-container justify-content-center">
                    <a href="https://www.facebook.com" class="social-item">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                <!-- </li>
                <li> -->
                    <a href="https://www.twitter.com" class="social-item">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="sticky-top bg-white">
        <div class="r-container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="<?=$us_url_root?>">
                        <div class="logo-container">
                            <img src="<?=$us_url_root?>assets/images/logo-white-background.svg" alt="Logo with white background" class="img-fluid">
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
                                <a class="nav-link" href="about_us.html">Om os</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.html">Kontakt</a>
                            </li>
                        </ul>
                        <div class="nav-item dropdown">
                            <?php if($user_id == 0){ ?>
                                <a class="nav-link dropdown-content dropdown-toggle" onclick="toggleMenuItem(this)" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Log ind / Registrer
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>/um/login.php">Log ind</a></li>
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>/um/join.php">Registrer som vikar</a></li>
                                </ul>                            
                            <?php } else { ?>
                                <a class="nav-link dropdown-content dropdown-toggle" onclick="toggleMenuItem(this)" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                    <?=$user_name?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>/bookings.php">Bookings</a></li>
                                    <?php if($user_permission == 2) { ?>
                                        <li><a class="dropdown-item" href="<?=$us_url_root?>/um/admin/users.php">Brugere</a></li>
                                    <?php } ?>
                                    <li><a class="dropdown-item with-top-separator" href="<?php echo $user_page_url.$user_id;?>">Min profil</a></li>
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>/um/logout.php">Log ud</a></li>
                                    
                                </ul>                                
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </section>
    <!-- End  of Header -->