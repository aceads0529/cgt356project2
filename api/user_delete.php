<?php
include '../includes/db.php';
include '../includes/utils.php';

if (!user_is_authorized('admin'))
    api_response(false, ERR_NOT_AUTHORIZED);

list($params, $num_empty) = safe_post_read('user-id');

if ($num_empty > 0)
    api_response(false, ERR_NO_USERID);

db_connect_query('DELETE FROM users WHERE UserId=?', $params['user-id']);

// TODO: Assert valid user ID

api_response(true);