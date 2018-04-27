<?php
include_once '../includes/db.php';
include_once '../includes/utils.php';
include_once '../includes/user.php';
include_once '../includes/image.php';

list($params, $num_empty) = safe_post_read('label', 'description?', 'category-id');

if (user_is_authorized($params['category-id'], AUTH_IMAGE_CREATE) && $num_empty == 0) {
    list($filename, $ratio) = upload_image($_FILES['upload'], $params['category-id']);

    if (!$filename)
        api_exit_response(false, 'Image upload failed');

    db_connect_query('INSERT INTO images (CategoryId, Label, Description, Filename, AspectRatio) VALUES (?, ?, ?, ?, ?)',
        $params['category-id'],
        $params['label'],
        $params['description'],
        $filename,
        $ratio);
}

redirect_back();
