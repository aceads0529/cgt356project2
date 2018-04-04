<?php
include '../includes/utils.php';
include '../includes/db.php';

$params = safe_post_read('username', 'password', 'acct-type', 'first-name', 'last-name');
$db = db_connect();

if (user_exists($db, $params['username'])) {
    api_response(false, sprintf('Username "%s" is already taken', $params['username']));
}

$salt = generate_salt();
$pswd_hash = md5($salt . $params['password']);

$result = db_query($db, 'INSERT INTO users (AcctType, Login, PswdHash, PswdSalt, FirstName, LastName) VALUES (?, ?, ?, ?, ?, ?)',
    $params['acct-type'],
    $params['username'],
    $pswd_hash,
    $salt,
    $params['first-name'],
    $params['last-name']);

db_close($db);
api_response(true);
