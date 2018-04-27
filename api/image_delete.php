<?php
include_once '../includes/user.php';
include_once '../includes/utils.php';
include_once '../includes/image.php';

list($params, $num_empty) = safe_post_read('image-id');

if ($num_empty > 0)
    api_response(false, ERR_NO_USERID);

if (!user_is_authorized($params['image-id'], AUTH_USER_DELETE))
    api_response(false, ERR_NOT_AUTHORIZED);

$db = db_connect();

if ($image = db_query($db, 'SELECT * FROM images WHERE ImageId=?', $params['image-id'])) {
    $image = $image->fetch_assoc();

    delete_image($image);
    db_connect_query('DELETE FROM images WHERE ImageId=?', $params['image-id']);
}

api_response(true);