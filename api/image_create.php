<?php
include '../includes/db.php';
include '../includes/utils.php';
include '../includes/image.php';

list($params, $num_empty) = safe_post_read('label', 'description?', 'category-id');

if(!user_is_authorized($params['category-id'],AUTH_IMAGE_CREATE))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_REQUIRED_FIELDS);

$filename = upload_image($_FILES['upload'], $params['category-id']);

if (!$filename)
    api_response(false, 'Image upload failed. Image must be a .jpg or .png');

db_connect_query('INSERT INTO images (CategoryId, Label, Description, Filename) VALUES (?, ?, ?, ?)',
    $params['category-id'],
    $params['label'],
    $params['description'],
    $filename);