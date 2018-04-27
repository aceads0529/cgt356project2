<?php

define('IMG_THUMB_DIRECTORY', '/image_uploads/thumb/');
define('IMG_LARGE_DIRECTORY', '/image_uploads/large/');

/**
 * Uploads an image file, resizing for large and thumbnail
 * Returns the image filename and aspect ratio, or false if unsuccessful
 *
 * @param array $file
 * @param int $category_id
 * @param string|bool $filename
 * @return array
 */
function upload_image($file, $category_id, $filename = false)
{
    if (!is_image($file))
        return [false, false];

    try {
        $image = image_from_file($file);

        $large = resize($image, 960, 960);
        $thumb = resize($image, 320, 320);

        if (!$filename) {
            $category = db_connect_query('SELECT Label FROM categories WHERE CategoryId=?', $category_id)->fetch_assoc();

            if (!$category)
                return [false, false];

            $filename = generate_filename($file, $category['Label']) . '.jpg';
        }

        // All files are stored as jpeg images
        imagejpeg($large, '..' . IMG_LARGE_DIRECTORY . $filename, 90);
        imagejpeg($thumb, '..' . IMG_THUMB_DIRECTORY . $filename, 90);

        return [$filename, imagesx($image) / imagesy($image)];
    } catch (mysqli_sql_exception $e) {
        return [false, false];
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
    return $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif';
}

/**
 * Returns a unique filename
 *
 * @param array $file
 * @param string $prefix
 * @return string
 */
function generate_filename($file, $prefix = 'image')
{
    // md5 ensures a unique filename every time
    return $prefix . '_' . md5($file['name'] . (string)time());
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

    // Call appropriate create function for each type of image file
    switch ($type) {
        case 'image/jpeg':
            $result = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $result = imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/gif':
            $result = imagecreatefromgif($file['tmp_name']);
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

    // If maximum size is greater than the image size, use original image size
    if ($src_width < $max_width && $src_height < $max_height) {
        $dst_width = $src_width;
        $dst_height = $src_height;
    } // Otherwise, calculate output image dimensions accordingly
    elseif ($src_ratio < 1) {
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

/**
 * Returns path to image (thumbnail)
 *
 * @param $image
 * @return string
 */
function get_img_thumb_path($image)
{
    return IMG_THUMB_DIRECTORY . $image['Filename'];
}

/**
 * Returns path to image (large)
 *
 * @param array $image
 * @return string
 */
function get_img_large_path($image)
{
    return IMG_LARGE_DIRECTORY . $image['Filename'];

}

/**
 * Deletes image files from uploads
 *
 * @param array $image
 */
function delete_image_files($image)
{
    unlink('..' . get_img_large_path($image));
    unlink('..' . get_img_thumb_path($image));
}