<?php
include '../includes/db.php';
include '../includes/utils.php';
include '../includes/image.php';

list($params, $num_empty) = safe_post_read('label', 'description?');

if ($num_empty > 0)
    api_response(false, 'Please fill out all required fields');

$filename = upload_image($_FILES['file']);

if (!$filename)
    api_response(false, 'Image upload failed');

