<?php
include_once 'db.php';

/**
 * Returns array of images belonging to category
 *
 * @param int $id
 * @return array|bool
 */
function get_category_images($id)
{
    $result = db_connect_query('SELECT * FROM images WHERE CategoryId=?', $id);

    if (!$result)
        return false;

    $arr = [];

    while ($row = $result->fetch_assoc())
        $arr[] = $row;

    return $arr;
}