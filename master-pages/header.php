<!DOCTYPE html>
<html lang="en">

<?php
    require_once __DIR__.'/master.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$pageTitle?>- <?=$site_name?> </title>
    <!-- Stylesheet -->
    <?php include_once __DIR__."/../config/css.php"?>
    <?php if (isset($extra_head_html)) echo $extra_head_html;?>
</head>

<body class="br-secondary-color">

    <div class="page-loader">
        <div class="spinner"></div>
    </div>
    
    <?php if(!isset($hideHeader)) { ?>

    <section class="sticky-top bg-white">
        <div class="r-container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="<?=$home_page_url?>">
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
                                <a class="nav-link active" aria-current="page" href="<?=$home_page_url?>">Hjem</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=$home_page_url?>#services-section">Ydelser</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=$home_page_url?>#courses-section">Kurser</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=$home_page_url?>#about-us-section">Om os</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=$contact_page_url?>">Kontakt</a>
                            </li>
                        </ul>                        
                        <p id="bi"><?=$user_id?></p>
                        <p id="bp"><?=$user_permission?></p>
                        <div class="nav-item dropdown">                            
                            <?php if($user_id == 0){ ?>
                                <a class="nav-link dropdown-content dropdown-toggle" onclick="toggleMenuItem(this)" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Log ind / Ansøg som vikar
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>um/login.php">Log ind</a></li>
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>applications/create-application.php">Ansøg som vikar</a></li>
                                </ul>                            
                            <?php } else { ?>
                                <a class="nav-link dropdown-content dropdown-toggle" onclick="toggleMenuItem(this)" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                    <?=$user_fullname?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?=$bookings_page_url?>">Bookings</a></li>
                                    <?php if($user_permission == 2) { ?>
                                        <li><a class="dropdown-item" href="<?=$us_url_root?>applications/list.php">Ansøgninger</a></li>
                                        <li><a class="dropdown-item" href="<?=$us_url_root?>um/admin/users.php">Brugere</a></li>
                                    <?php } ?>
                                    <?php if($user_permission == 2 || $user_permission == 3) { ?>
                                        <li><a class="dropdown-item" href="<?=$chat_page_url?>">Chat</a></li>
                                    <?php } ?>
                                    <li><a class="dropdown-item with-top-separator" href="<?php echo $user_page_url.$user_id;?>">Min profil</a></li>
                                    <li><a class="dropdown-item" href="<?=$us_url_root?>um/logout.php">Log ud</a></li>
                                </ul>                                
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </section>
    <?php } ?>
    <!-- End  of Header -->