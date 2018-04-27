<?php
include_once '../includes/utils.php';
include_once '../includes/db.php';

safe_session_start();

list($params, $num_empty) = safe_post_read('username', 'password');

if ($num_empty > 0)
    api_exit_response(false, 'Please provide a username and password');

$user = db_connect_query('SELECT * FROM users WHERE Login=?', $params['username']);
$login_success = false;

if ($user && $user->num_rows > 0) {
    $user = $user->fetch_assoc();

    $pswd_hash = md5($user['PswdSalt'] . $params['password']);

    if ($pswd_hash == $user['PswdHash']) {
        $_SESSION['user-id'] = $user['UserId'];
        $login_success = true;
    }
}

$remote_address = $_SERVER['REMOTE_ADDR'];
$http_host = $_SERVER['HTTP_HOST'];
$attempted_login = $params['username'];
$http_user_agent = $_SERVER['HTTP_USER_AGENT'];

db_connect_query('INSERT INTO logging (RemoteAddress, HttpHost, AttemptedLogin, HttpUserAgent, LoginSuccess) VALUES (?, ?, ?, ?, ?)',
    $remote_address,
    $http_host,
    $attempted_login,
    $http_user_agent,
    $login_success ? 1 : 0);

if ($login_success)
    api_exit_response(true);
else
    api_exit_response(false, 'Username or password is incorrect');
