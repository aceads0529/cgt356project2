<?php
include '../includes/user.php';
include '../includes/utils.php';

list($params, $num_empty) = safe_post_read('category-id', 'label', 'description?');

if (!user_is_authorized($params['category-id'], AUTH_CATEGORY_EDIT))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_REQUIRED_FIELDS);

db_connect_query('UPDATE categories SET Label=?, Description=? WHERE CategoryId=?', $params['label'], $params['description'], $params['category-id']);
api_response(true);