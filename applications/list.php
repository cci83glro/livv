<?php
    ini_set('allow_url_fopen', 1);
    header('X-Frame-Options: DENY');
    $public = false;
    $permissions = [2];
    require_once __DIR__.'/../master-pages/header.php';

?>

<section class="section page-banner position-relative" style="background-image: url(<?=$us_url_root.'/assets/images/applications-page-banner.webp'?>);">
    <div class="r-container">
        <div class="image-overlay"></div>
        <div class="page-title position-relative" style="z-index: 2;">
            <h1 class="font-1 fw-bold text-white">Ansøgninger</h1>
        </div>
    </div>
</section>

<section class="container">
    <h3>Nye ansøgninger</h2>
    <div class="category applications-list" id="unhandled-container">

        <!-- if ($has_more_unhandled) { ?>
        <button id="show-more-unhandled">Show More</button>
        -->
    </div>

    <h3>Behandlede ansøgninger</h2>
    <div class="category applications-list" id="handled-container">        
 
        <!-- if ($has_more_handled) { ?>
        <button id="show-more-handled">Vis mere</button>
         -->
    </div>
</section>


<?php include_once __DIR__.'/../master-pages/footer.php'?>
<script src="<?=$us_url_root?>assets/js/applications.js"></script>

</body>
</html>