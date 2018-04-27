<?php
include_once '../includes/user.php';
include_once '../includes/utils.php';

list($params, $num_empty) = safe_post_read('category-id', 'label', 'description?');

if ($num_empty > 0)
    api_exit_response(false, ERR_REQUIRED_FIELDS);

if (!user_is_authorized($params['category-id'], AUTH_CATEGORY_EDIT))
    api_exit_response(false, ERR_NOT_AUTHORIZED);

db_connect_query('UPDATE categories SET Label=?, Description=? WHERE CategoryId=?', $params['label'], $params['description'], $params['category-id']);
api_exit_response(true);
