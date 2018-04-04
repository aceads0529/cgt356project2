<?php
include '../includes/utils.php';
include '../includes/db.php';

$params = safe_post_read('username', 'password');

$db = db_connect();
$user = db_query($db, 'SELECT * FROM users WHERE Login=?', $params['username']);

if ($user->num_rows > 0) {
    $user = $user->fetch_assoc();

    $pswd_hash = md5($user['PswdSalt'] . $params['password']);

    if ($pswd_hash == $user['PswdHash']) {
        api_response(true);
    }
}

api_response(false, 'Username or password is incorrect');