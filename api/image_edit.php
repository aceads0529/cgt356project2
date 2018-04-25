<?php
include '../includes/db.php';
include '../includes/utils.php';
include '../includes/image.php';

print_r($_FILES);

list($params, $num_empty) = safe_post_read('image-id', 'label', 'description?', 'category-id');

if(!user_is_authorized($params['category-id'],AUTH_IMAGE_EDIT))
    api_response(false, ERR_NOT_AUTHORIZED);

if ($num_empty > 0)
    api_response(false, ERR_REQUIRED_FIELDS);

$db = db_connect();

if ($image = db_query($db, 'SELECT * FROM images WHERE ImageId=?', $params['image-id'])) {
    $image = $image->fetch_assoc();
    if (isset($_FILES['upload']) && $_FILES['upload']['error'] == UPLOAD_ERR_OK)
        upload_image($_FILES['upload'], $params['category-id'], $image['Filename']);

    db_query($db, 'UPDATE images SET Label=?, Description=?, CategoryId=?', $params['label'], $params['description'], $params['category-id']);
} else {
    $filename = upload_image($_FILES['upload'], $params['category-id']);

    if (!$filename)
        api_response(false, 'Image upload failed');

    db_connect_query('INSERT INTO images (CategoryId, Label, Description, Filename) VALUES (?, ?, ?, ?)',
        $params['category-id'],
        $params['label'],
        $params['description'],
        $filename);
}