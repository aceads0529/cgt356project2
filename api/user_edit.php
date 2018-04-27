<?php
include_once '../includes/utils.php';
include_once '../includes/user.php';
include_once '../includes/db.php';

list($params, $num_empty) = safe_post_read('user-id', 'username', 'password?', 'first-name', 'last-name', 'categories?');

if (!user_is_authorized($params['user-id'], AUTH_USER_EDIT))
    api_exit_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_exit_response(false, ERR_NO_USERID);

$db = db_connect();
$user = db_query($db, 'SELECT * FROM users WHERE UserId=?', $params['user-id'])->fetch_assoc();

if (row_exists('users', 'Login', $params['username']))
    api_exit_response(false, sprintf('Username "%s" is already taken', $params['username']));

// If new password is provided, salt and hash
if (!empty($params['password'])) {
    $params['password'] = md5($user['PswdSalt'] . $params['password']);
}

list($query, $values) = create_update_query('users',
    array('Login', 'PswdHash', 'FirstName', 'LastName'),
    array($params['username'], $params['password'], $params['first-name'], $params['last-name']));

if (!empty($query)) {
    // Append WHERE clause
    $query .= 'WHERE UserId=?';
    $values[] = $params['user-id'];

    db_query($db, $query, $values);
}

db_close($db);

$active_user = get_active_user();

if ($active_user && $active_user['AcctType'] == 'ADMIN') {
    set_user_categories($params['user-id'], $params['categories']);
}

api_exit_response(true);
