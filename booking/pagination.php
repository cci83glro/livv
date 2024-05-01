<?php
// Function to generate pagination links
function generatePaginationLinks($currentPage, $totalPages, $url) {
    $pagination = ''; // Initialize empty pagination string

    // Generate "Previous" link if not on first page
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $pagination .= "<a href='{$url}?page={$prevPage}'>&laquo; Previous</a>";
    }

    // Generate numeric pagination links
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = ($i == $currentPage) ? 'active' : '';
        $pagination .= "<a class='{$activeClass}' href='{$url}?page={$i}'>{$i}</a>";
    }

    // Generate "Next" link if not on last page
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $pagination .= "<a href='{$url}?page={$nextPage}'>Next &raquo;</a>";
    }

    return $pagination;
}

// Example usage:
// $currentPage = 1; // Current page number
// $totalPages = 5; // Total number of pages
// $url = 'example.php'; // URL of the page
// echo generatePaginationLinks($currentPage, $totalPages, $url);
?>