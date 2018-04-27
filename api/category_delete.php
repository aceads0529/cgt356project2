<?php
include_once '../includes/user.php';
include_once '../includes/utils.php';

list($params, $num_empty) = safe_post_read('category-id');

if ($num_empty > 0)
    api_exit_response(false, ERR_NO_CATEGORYID);

if(!user_is_authorized(null,AUTH_CATEGORY_DELETE))
    api_exit_response(false, ERR_NOT_AUTHORIZED);

db_connect_query('DELETE FROM categories WHERE CategoryId=?', $params['category-id']);
api_exit_response(true);
