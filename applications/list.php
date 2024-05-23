<?php
    ini_set('allow_url_fopen', 1);
    header('X-Frame-Options: DENY');
    $public = false;
    $permissions = [2];
    require_once __DIR__.'/../master-pages/header.php';

    $db = DB::getInstance();

    // Handle marking applications as handled
    if (isset($_POST['mark_handled'])) {
        $id = intval($_POST['id']);
        $update_sql = "UPDATE applications SET handled = b'1' WHERE id = $id";
        $db->query($update_sql);

        $unhandled_list = fetch_records($db, false, 0, 10);
        $handled_list = fetch_records($db, true, 0, 10);

        echo json_encode([
            'unhandled_list' => $unhandled_list,
            'handled_list' => $handled_list
        ]);
        exit;
    }

    // Fetch records based on the category and offset
    function fetch_records($db, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT a.*, q.qualification_name as qualification FROM applications a LEFT JOIN qualifications q ON a.qualification_id = q.qualification_id
                WHERE handled = $handled_value ORDER BY dateCreated DESC LIMIT $limit OFFSET $offset";
        return $db->query($sql)->results();
    }

    function has_more_records($db, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT COUNT(*) as count FROM applications WHERE handled = $handled_value";
        $result = $db->query($sql)->results();
        return $result[0]->count > $offset + $limit;
    }

    // AJAX request to fetch more records
    if (isset($_GET['ajax']) && isset($_GET['handled'])) {
        $handled = intval($_GET['handled']);
        $offset = intval($_GET['offset']);
        $limit = 10;

        $result = fetch_records($db, $handled, $offset, $limit);
        $applications = [];
        foreach ($result as $row) {
            $applications[] = $row;
        }

        echo json_encode($applications);
        exit;
    }

    // Initial page load
    $limit = 10;
    $offset_handled = 0;
    $offset_unhandled = 0;
    $result_handled = fetch_records($db, true, $offset_handled, $limit);
    $result_unhandled = fetch_records($db, false, $offset_unhandled, $limit);
    $has_more_handled = has_more_records($db, true, $offset_handled, $limit);
    $has_more_unhandled = has_more_records($db, false, $offset_unhandled, $limit);
?>

<div class="container">
    <div class="category applications-list" id="unhandled-container">
        <h2>Nye ansøgninger</h2>
        <?php if (sizeof($result_unhandled) == 0) { ?>
            <p>Ingen ansøgninger</p>
        <?php } ?>
        <?php foreach ($result_unhandled as $row) { ?>
            <div class="record">
                <p><span>ID:</span> <?php echo $row->id; ?></p>
                <p><span>Navn:</span> <?php echo $row->fname . " " . $row->lname; ?></p>
                <p><span>Telefon:</span> <?php echo $row->phone; ?></p>
                <p><span>Email:</span> <?php echo $row->email; ?></p>
                <p><span>Uddannelse:</span> <?php echo $row->qualification; ?></p>
                <p><span>Antal års erfaring:</span> <?php echo $row->experience; ?></p>
                <p><span>Reference kontakt:</span> <?php echo $row->namePhoneReference; ?></p>
                <form method="post" class="mark-handled-form" data-id="<?php echo $row->id; ?>">
                    <div class="form-actions">
                        <button class="save" type="submit" name="mark_handled">Marker som behandlet</button>
                    </div>
                </form>
                <a class="create-account" target=_blank href="<?=$users_page_url?>?create=1&fname=<?php echo $row->fname; ?>&lname=<?php echo $row->lname; ?>&email=<?php echo $row->email; ?>&phone=<?php echo $row->phone; ?>">Opret brugerkonto</a>
            </div>
        <?php } ?>
        <!-- if ($has_more_unhandled) { ?>
        <button id="show-more-unhandled">Show More</button>
        -->
    </div>

    <div class="category applications-list" id="handled-container">
        <h2>Behandlede ansøgninger</h2>
        <?php if (sizeof($result_handled) == 0) { ?>
            <p>Ingen ansøgninger</p>
        <?php } ?>
        <?php foreach ($result_handled as $row) { ?>
            <div class="record">
                <p><span>ID:</span> <?php echo $row->id; ?></p>
                <p><span>Navn:</span> <?php echo $row->fname . " " . $row->lname; ?></p>
                <p><span>Telefon:</span> <?php echo $row->phone; ?></p>
                <p><span>Email:</span> <?php echo $row->email; ?></p>
                <p><span>Uddannelse:</span> <?php echo $row->qualification; ?></p>
                <p><span>Antal års erfaring:</span> <?php echo $row->experience; ?></p>
                <p><span>Reference kontakt:</span> <?php echo $row->namePhoneReference; ?></p>
            </div>
        <?php }
        if ($has_more_handled) { ?>
        <button id="show-more-handled">Vis mere</button>
        <?php } ?>
    </div>
</div>


<?php include_once $abs_us_root.$us_url_root."master-pages/footer.php"?>
<script src="<?=$us_url_root?>assets/js/applications.js"></script>

</body>
</html>