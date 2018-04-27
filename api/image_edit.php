<?php
include_once '../includes/db.php';
include_once '../includes/utils.php';
include_once '../includes/image.php';
include_once '../includes/user.php';

list($params, $num_empty) = safe_post_read('image-id', 'label', 'description?', 'category-id');

if (user_is_authorized($params['category-id'], AUTH_IMAGE_EDIT) && $num_empty == 0) {
    $db = db_connect();

    if ($image = db_query($db, 'SELECT * FROM images WHERE ImageId=?', $params['image-id'])) {
        $image = $image->fetch_assoc();
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] == UPLOAD_ERR_OK) {
            list($filename, $ratio) = upload_image($_FILES['upload'], $params['category-id']);
            if ($filename) {
                delete_image($image);
                db_query($db, 'UPDATE images SET Filename=?, AspectRatio=? WHERE ImageId=?', $filename, $ratio, $params['image-id']);
            }
        }

        db_query($db, 'UPDATE images SET Label=?, Description=?, CategoryId=? WHERE ImageId=?', $params['label'], $params['description'], $params['category-id'], $params['image-id']);
    }
}

header('Location: /gallery_category.php?category-id=' . $params['category-id']);