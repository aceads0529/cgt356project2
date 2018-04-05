<?php
include '../includes/db.php';
include '../includes/utils.php';

// TODO: Authorization

list($params, $num_empty) = safe_post_read('category-id');

if ($num_empty > 0)
    api_response(false, ERR_NO_CATEGORYID);

db_connect_query('DELETE FROM categories WHERE CategoryId=?', $params['category-id']);
api_response(true);
