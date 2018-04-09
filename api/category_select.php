<?php
include '../includes/utils.php';
include '../includes/user.php';

list($params, $num_empty) = safe_post_read('selection?');

$where_clause = parse_selection_range($params['selection'], 'CategoryId');
$query = db_connect_query('SELECT * FROM categories' . $where_clause);

if (!$query)
    api_response(false, 'Invalid selection range');

$categories = [];

header('Content-Type: application/json');
while ($row = $query->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode(array('success' => true, 'error' => '', 'categories' => $categories));
exit;