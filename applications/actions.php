<?php

    $public = false;
    $permissions = [2];
    require_once __DIR__.'/../helpers/dbo.php';

    $dbo = dbo::getInstance();

    function getRefreshedLists($dbo) {
        $offset = 0;
        $limit = 10;

        return [
            'unhandled_list' => fetch_records($dbo, false, $offset, $limit),
            'handled_list' => fetch_records($dbo, true, $offset, $limit)
        ];
    }

    // Handle marking applications as handled
    if (isset($_POST['mark_handled'])) {
        $id = intval($_POST['id']);
        $update_sql = "UPDATE applications SET handled = b'1' WHERE id = $id";
        $dbo->query($update_sql);

        echo json_encode(getRefreshedLists($dbo));
        exit;
    }

    // Fetch records based on the category and offset
    function fetch_records($dbo, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT a.*, q.qualification_name as qualification FROM applications a LEFT JOIN qualifications q ON a.qualification_id = q.qualification_id
                WHERE handled = $handled_value ORDER BY dateCreated DESC LIMIT $limit OFFSET $offset";
        return $dbo->query($sql)->fetchAll();
    }

    function has_more_records($dbo, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT COUNT(*) as count FROM applications WHERE handled = $handled_value";
        $result = $dbo->query($sql)->fetchAll();
        return $result[0]['count'] > $offset + $limit;
    }

    if (isset($_GET['ajax']) && isset($_GET['initial'])) {
        echo json_encode(getRefreshedLists($dbo));
    }

    if (isset($_GET['ajax']) && isset($_GET['handled'])) {
        $handled = intval($_GET['handled']);
        $offset = intval($_GET['offset']);
        $limit = 10;

        $result = fetch_records($dbo, $handled, $offset, $limit);
        $applications = [];
        foreach ($result as $row) {
            $applications[] = $row;
        }

        echo json_encode($applications);
        exit;
    }
?>
