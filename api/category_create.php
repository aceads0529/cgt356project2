<?php
include_once '../includes/db.php';
include_once '../includes/user.php';
include_once '../includes/utils.php';

list($params, $num_empty) = safe_post_read('label', 'description?');

if ($num_empty > 0)
    api_exit_response(false, ERR_REQUIRED_FIELDS);

if (!user_is_authorized(null, AUTH_CATEGORY_CREATE))
    api_exit_response(false, ERR_NOT_AUTHORIZED);

$db = db_connect();

if (row_exists('categories', 'Label', $params['label']))
    api_exit_response(false, sprintf('Category "%s" already exists', $params['label']));

$result = db_query($db, 'INSERT INTO categories (Label, Description) VALUES (?, ?)', $params['label'], $params['description']);

db_close($db);
api_exit_response(true);
