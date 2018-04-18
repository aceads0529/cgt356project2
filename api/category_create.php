<?php
include '../includes/user.php';
include '../includes/utils.php';

list($params, $num_empty) = safe_post_read('label', 'description?');

if(!user_is_authorized(null,AUTH_CATEGORY_CREATE))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_REQUIRED_FIELDS);

$db = db_connect();

if (category_exists($db, $params['label']))
    api_response(false, sprintf('Category "%s" already exists', $params['label']));

$result = db_query($db, 'INSERT INTO categories (Label, Description) VALUES (?, ?)', $params['label'], $params['description']);

db_close($db);
api_response(true);