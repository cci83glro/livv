<!DOCTYPE html>
<html lang="en">

<?php
    require_once __DIR__.'/master.php';
?>
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="<?php echo $description; ?>" />
	<meta name="robots" content="max-image-preview:large" />
	<meta name="author" content="Trepavo Creative Agency">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$pageTitle?>- <?=$site_name?> </title>
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
                    <div class="nav-item dropdown user-info mobile-only">
                        <?php include __DIR__."/user-info.php"?>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto font-2 fw-semibold gap-lg-3">
                            <li class="nav-item">
                                <a class="nav-link <?php if ($pageTitle=='Hjem') echo 'active';?>" aria-current="page" href="<?=$home_page_url?>">Hjem</a>
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
                                <a class="nav-link <?php if ($pageTitle=='Kontakt') echo 'active';?>" href="<?=$contact_page_url?>">Kontakt</a>
                            </li>
                        </ul>
                        <p id="bi"><?=$user_id?></p>
                        <p id="bp"><?=$user_permission?></p>
                        <div class="nav-item dropdown user-info desktop-only">
                            <?php include __DIR__."/user-info.php"?>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </section>
    <?php } ?>