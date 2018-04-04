<?php

/**
 * Uploads an image file, resizing for large and thumbnail
 *
 * @param array $file
 * @return bool|string
 */
function upload_image($file)
{
    if (!is_image($file))
        return false;

    try {
        $image = image_from_file($file);

        $large = resize($image, 960, 960);
        $thumb = resize($image, 320, 320);

        $filename = generate_filename($_FILES['file']) . '.jpg';

        imagejpeg($large, '../image_uploads/large/' . $filename, 90);
        imagejpeg($thumb, '../image_uploads/thumb/' . $filename, 90);

        return $filename;
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

/**
 * Returns whether a file is an image
 *
 * @param array $file
 * @return bool
 */
function is_image($file)
{
    $type = $file['type'];
    return $type == 'image/jpeg' || $type == 'image/png';
}

/**
 * Returns a unique filename
 *
 * @param array $file
 * @return string
 */
function generate_filename($file)
{
    return md5($file['name'] . (string)time());
}

/**
 * Returns an image resource
 *
 * @param array $file
 * @return bool|resource
 */
function image_from_file($file)
{
    $type = $file['type'];
    $result = false;

    switch ($type) {
        case 'image/jpeg':
            $result = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $result = imagecreatefrompng($file['tmp_name']);
            break;
    }

    return $result;
}

/**
 * Returns an image resized to maximum width or height. If $max_width and $max_height
 * are greater than the image width and height, a copy of the image is returned
 *
 * @param resource $image
 * @param int $max_width
 * @param int $max_height
 * @return resource
 */
function resize($image, $max_width, $max_height)
{
    $src_width = imagesx($image);
    $src_height = imagesy($image);


    $src_ratio = $src_width / (double)$src_height;

    if ($src_width < $max_width && $src_height < $max_height) {
        $dst_width = $src_width;
        $dst_height = $src_height;
    } elseif ($src_ratio < 1) {
        $dst_width = $max_height * $src_ratio;
        $dst_height = $max_height;
    } else {
        $dst_width = $max_width;
        $dst_height = $max_width / $src_ratio;
    }

    $result = imagecreatetruecolor($dst_width, $dst_height);
    imagecopyresampled($result, $image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

    return $result;
}