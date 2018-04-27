<?php
include_once '../includes/utils.php';
include_once '../includes/user.php';

list($params, $num_empty) = safe_post_read('selection?');

$where_clause = parse_selection_range($params['selection'], 'UserId');

$db = db_connect();

$user_query = db_query($db, 'SELECT UserId, Login, AcctType, FirstName, LastName FROM users' . $where_clause);

if (!$user_query)
    api_response(false, 'Invalid selection range');

$users = [];

header('Content-Type: application/json');
while ($user = $user_query->fetch_assoc()) {
    $cat_query = db_query($db, 'SELECT CategoryId FROM permissions WHERE UserId=?', $user['UserId']);

    while ($cat = $cat_query->fetch_assoc()) {
        $user['categories'][] = $cat['CategoryId'];
    }

    $users[] = $user;
}

db_close($db);

echo json_encode(array('success' => true, 'error' => '', 'users' => $users));
exit;
