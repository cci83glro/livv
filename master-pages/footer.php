<?php if(!isset($hideFooter)) { ?>
    <footer>
        <section class="px-lg-0 px-4 py-lg-5 py-4 bg-accent-color-1">
            <div class="r-container text-white">
                <div class="row row-cols-1 row-cols-lg-4">
                    <div class="col col-sm-6 col-lg-3 logo">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="logo-container">
                                <img src="<?=$us_url_root?>assets/images/logo-blue-background.svg" alt="Logo with blue background" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="col col-sm-6 col-lg-3 links">
                        <div class="d-flex flex-column">
                            <h5 class="font-1 fw-bold mb-3">Links</h5>
                            <div class="d-flex flex-column gap-2">
                                <a href="<?=$home_page_url?>" class="d-flex flex-row gap-2 align-items-center link-light">Home</a>
                                <a href="#services-section" class="d-flex flex-row gap-2 align-items-center link-light">Ydelser</a>
                                <a href="#courses-section" class="d-flex flex-row gap-2 align-items-center link-light">Kurser</a>
                                <a href="#about-us-section" class="d-flex flex-row gap-2 align-items-center link-light">Om Os</a>
                                <a href="#contact-section" class="d-flex flex-row gap-2 align-items-center link-light">Kontakt</a>
                            </div>
                        </div>
                    </div>               
                    <div class="col col-sm-12 col-lg-6 contact">
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
                <div class="text-center p-2">© Copyright <?php echo date("Y"); ?> - <?php echo $site_name; ?></div>
            </div>
        </section>
    </footer>
<?php } ?>

<div id="scroll-top">
  <i class="fa-solid fa-arrow-up"></i>
</div>
<aside id="aside-contact-widgets">
  <a target=_blank href="<?=$facebook_url?>">
    <div class="facebook-link ">
      <i class="fab fa-facebook-f"></i>
    </div>
  </a>
  <a target=_blank href="<?=$instagram_url?>">
    <div class="linkedin-link ">
      <i class="fab fa-instagram"></i>
    </div>
  </a>
  <a href="tel:<?=$company_phone?>">
    <div class="call-us ">
      <i class="fa-solid fa-phone"></i>
    </div>
  </a>
  <a href="mailto:<?=$company_contact_email?>">
    <div class="mail-us ">
      <i class="fa fa-envelope"></i>
    </div>
  </a>
</aside>

<?php include_once __DIR__."/../config/js.php"?>
