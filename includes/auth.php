<?php
include_once 'db.php';

/**
 * Returns user or category from $_GET parameter, or false if unsuccessful
 *
 * @param string $param
 * @return bool
 */
function get_from_id($param)
{
    if (!isset($_GET[$param]))
        return false;

    $result = false;

    switch ($param) {
        case 'user-id':
            $result = db_connect_query('SELECT * FROM users WHERE UserId=?', $param);
            break;
        case 'category-id':
            $result = db_connect_query('SELECT * FROM categories WHERE CategoryId=?', $param);
            break;
    }

    return $result && $result->num_rows > 0;
}