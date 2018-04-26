<?php
include_once '../includes/user.php';
include_once '../includes/utils.php';

list($params, $num_empty) = safe_post_read('user-id');

if (!user_is_authorized($params['user-id'], AUTH_USER_DELETE))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_NO_USERID);

db_connect_query('DELETE FROM users WHERE UserId=?', $params['user-id']);

// TODO: Assert valid user ID

api_response(true);