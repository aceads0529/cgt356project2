<?php
include '../includes/user.php';
include '../includes/utils.php';

list($params, $num_empty) = safe_post_read('category-id');

if(!user_is_authorized(null,AUTH_CATEGORY_DELETE))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_NO_CATEGORYID);

db_connect_query('DELETE FROM categories WHERE CategoryId=?', $params['category-id']);
api_response(true);
