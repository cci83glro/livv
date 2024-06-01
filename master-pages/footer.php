<?php if(!isset($hideFooter)) { ?>
    <footer>
        <section class="px-lg-0 px-4 py-lg-5 py-4 bg-accent-color-1">
            <div class="r-container text-white">
                <div class="row row-cols-1">
                    <div class="col col-sm-12 col-md-4 logo">
                        <img src="<?=$us_url_root?>assets/images/logo-blue-background.svg" alt="Logo with blue background" class="img-fluid">
                    </div>
                    <div class="col col-sm-6 col-md-4 links">
                        <h5 class="font-1 fw-bold mb-3">Links</h5>
                        <div class="d-flex">
                            <div class="links-group">
                                <a href="<?=$home_page_url?>" class="align-items-center">Home</a>
                                <a href="#services-section" class="align-items-center">Ydelser</a>
                                <a href="#courses-section" class="align-items-center">Kurser</a>
                                <a href="#about-us-section" class="align-items-center">Om Os</a>
                            </div>
                            <div class="links-group">
                                <a href="#contact-section" class="align-items-center">Kontakt</a>
                                <a href="#" class="align-items-center">Cookies politik</a>
                                <a href="#" class="align-items-center">Persondata politik</a>                            
                            </div>
                        </div>
                    </div>               
                    <div class="col col-sm-6 col-md-4 contact">
                        <div class="d-flex flex-column mb-3">
                            <h5 class="font-1 fw-bold mb-1">Kontakt os</h5>
                            <div class="align-items-center contact-detail">
                                <a class="location" target=_blank href="<?=$company_address_google_url?>">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?=$company_address?>
                                </a>
                            </div>
                            <div class="align-items-center contact-detail">
                                <a class="phone" href="tel:<?=$company_phone?>">
                                    <i class="fa-solid fa-phone"></i>
                                    <?=$company_phone_display?>
                                </a>
                            </div>
                            <div class="align-items-center contact-detail">
                                <a class="email" href="mailto:<?=$company_contact_email?>">
                                    <i class="fa-solid fa-envelope"></i>
                                    <?=$company_contact_email?>
                                </a>
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
