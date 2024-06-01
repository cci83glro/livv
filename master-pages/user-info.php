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
