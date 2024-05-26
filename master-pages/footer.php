<?php if(!isset($hideHeaderAndFooter)) { ?>
    <footer>
        <section class="px-lg-0 px-4 py-lg-5 py-4 bg-accent-color-1">
            <div class="r-container text-white">
                <div class="row row-cols-1 row-cols-lg-4">
                    <div class="col col-lg-3 mb-3">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="logo-container">
                                <img src="<?=$us_url_root?>assets/images/logo-blue-background.svg" alt="Logo with blue background" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="col col-lg-3 mb-3">
                        <div class="d-flex flex-column">
                            <h5 class="font-1 fw-bold mb-3">Links</h5>
                            <div class="d-flex flex-column gap-2">
                                <a href="<?=$home_page_url?>" class="d-flex flex-row gap-2 align-items-center link-light">Home</a>
                                <a href="#" class="d-flex flex-row gap-2 align-items-center link-light">Ydelser</a>
                                <a href="#" class="d-flex flex-row gap-2 align-items-center link-light">Kurser</a>
                                <a href="#" class="d-flex flex-row gap-2 align-items-center link-light">Om Os</a>
                                <a href="#" class="d-flex flex-row gap-2 align-items-center link-light">Kontakt</a>
                            </div>
                        </div>
                    </div>               
                    <div class="col col-lg-6 mb-3">
                        <div class="d-flex flex-column mb-3">
                            <h5 class="font-1 fw-bold mb-1">Kontakt os</h5>
                            <div class="d-flex flex-wrap">
                                <div class="d-flex flex-row gap-2 align-items-center contact-detail">
                                    <a class="location" target=_blank href="<?=$company_address_google_url?>">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <?=$company_address?>
                                    </a>
                                </div>
                                <div class="d-flex flex-row gap-2 align-items-center contact-detail">
                                    <a class="phone" href="tel:<?=$company_phone?>">
                                        <i class="fa-solid fa-phone"></i>
                                        <?=$company_phone_display?>
                                    </a>
                                </div>
                                <div class="d-flex flex-row gap-2 align-items-center contact-detail">
                                    <a class="email" href="mailto:<?=$company_contact_email?>">
                                        <i class="fa-solid fa-envelope"></i>
                                        <?=$company_contact_email?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-3">
                            <div class="social-container">
                                <a href="<?=$facebook_url?>" class="social-item">
                                    <i class="fa-brands fa-facebook"></i>
                                </a>
                                <a href="<?=$instagram_url?>" class="social-item">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100" style="border-bottom: 1px solid var(--primary-color);"></div>
                <div class="text-center p-2">©Copyright <?php echo date("Y"); ?> - LivVikar</div>
            </div>
        </section>
    </footer>
<?php } ?>
<?php include_once __DIR__."/../config/js.php"?>
