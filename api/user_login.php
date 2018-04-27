<?php
include_once '../includes/utils.php';
include_once '../includes/db.php';

safe_session_start();

list($params, $num_empty) = safe_post_read('username', 'password');
$user = db_connect_query('SELECT * FROM users WHERE Login=?', $params['username']);

if ($user && $user->num_rows > 0) {
    $user = $user->fetch_assoc();

    $pswd_hash = md5($user['PswdSalt'] . $params['password']);

    if ($pswd_hash == $user['PswdHash']) {
        $_SESSION['user-id'] = $user['UserId'];
        api_response(true);
    }
}

api_response(false, 'Username or password is incorrect');