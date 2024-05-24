<?php

    $public = false;
    $permissions = [2];
    require_once __DIR__.'/../helpers/db.php';

    function getRefreshedLists($db) {
        $offset = 0;
        $limit = 10;

        return [
            'unhandled_list' => fetch_records($db, false, $offset, $limit),
            'handled_list' => fetch_records($db, true, $offset, $limit)
        ];
    }

    // Handle marking applications as handled
    if (isset($_POST['mark_handled'])) {
        $id = intval($_POST['id']);
        $update_sql = "UPDATE applications SET handled = b'1' WHERE id = $id";
        $db->query($update_sql);

        echo json_encode(getRefreshedLists($db));
        exit;
    }

    // Fetch records based on the category and offset
    function fetch_records($db, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT a.*, q.qualification_name as qualification FROM applications a LEFT JOIN qualifications q ON a.qualification_id = q.qualification_id
                WHERE handled = $handled_value ORDER BY dateCreated DESC LIMIT $limit OFFSET $offset";
        return $db->query($sql)->fetchAll();
    }

    function has_more_records($db, $handled, $offset, $limit) {
        $handled_value = $handled ? "b'1'" : "b'0'";
        $sql = "SELECT COUNT(*) as count FROM applications WHERE handled = $handled_value";
        $result = $db->query($sql)->fetchAll();
        return $result[0]->count > $offset + $limit;
    }

    if (isset($_GET['ajax']) && isset($_GET['initial'])) {
        echo json_encode(getRefreshedLists($db));
    }

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
?>
