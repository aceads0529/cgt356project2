<?php


/**
 * Returns POST values along with the number of empty values. If a POST value is unset, the value returned is an empty string
 *
 * @param array ...$vars
 * @return array
 */
function safe_post_read(...$vars)
{
    $result = [];
    $num_empty = 0;

    foreach ($vars as $v) {
        $allow_empty = false;

        if ($v[strlen($v) - 1] == '?') {
            $v = substr($v, 0, strlen($v) - 1);
            $allow_empty = true;
        }

        if (isset($_POST[$v])) {
            $result[$v] = $_POST[$v];
            $num_empty += empty($_POST[$v]) && !$allow_empty ? 1 : 0;
        } else {
            $result[$v] = '';
            $num_empty += $allow_empty ? 0 : 1;
        }
    }

    return array($result, $num_empty);
}


/**
 * Returns a random sequence of 16 bytes
 *
 * @return string
 */
function generate_salt()
{
    try {
        return (string)random_bytes(16);
    } catch (Exception $e) {
        return '0000000000000000';
    }
}

/**
 * Sets 'Content-Type' to JSON and echoes a response object
 *
 * @param bool $success
 * @param string $error
 */
function api_response($success, $error = '')
{
    header('Content-Type: application/json');
    echo json_encode(array('success' => $success, 'error' => $error));
    exit;
}


/**
 * Returns whether a username already exists in the database
 *
 * @param $db
 * @param $username
 * @return bool
 */
function user_exists($db, $username)
{
    return db_query($db, 'SELECT * FROM users WHERE Login=?', $username)->num_rows > 0;
}