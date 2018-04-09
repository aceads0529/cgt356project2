<?php

// Error messages
define('ERR_REQUIRED_FIELDS', 'Please fill out all required fields');
define('ERR_NOT_AUTHORIZED', 'You are not authorized to perform this action');
define('ERR_NO_USERID', 'No user ID was given');
define('ERR_NO_CATEGORYID', 'No category ID was given');

/**
 * Start session if it hasn't been started yet
 */
function safe_session_start()
{
    if (session_status() == PHP_SESSION_NONE)
        session_start();
}

/**
 * Returns POST values along with the number of empty values. If a POST value is unset, the value returned is an empty string
 * Append a '?' to a variable name to make it optional (i.e. not count towards number of empty values)
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

        // If variable ends with question mark, remove it and prevent variable from being counted as empty
        if ($v[strlen($v) - 1] == '?') {
            $v = substr($v, 0, strlen($v) - 1);
            $allow_empty = true;
        }

        // If $_POST variable is not set, store empty string
        // Increment number of empties (if necessary)
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
 * @param mysqli $db
 * @param string $category
 * @return bool
 */
function category_exists($db, $category)
{
    return db_query($db, 'SELECT * FROM categories WHERE Label=?', $category)->num_rows > 0;
}

/**
 * Builds an UPDATE query string, only including columns whose given values are not empty
 *
 * @param string $table
 * @param array $columns
 * @param array $values
 * @return array
 */
function create_update_query($table, $columns, $values)
{
    $set_clause = '';

    // If # of columns does not match # of values, iterate for the smaller of the two
    $max = min(count($columns), count($values));

    $result_values = []; // Store non-empty values to be returned

    for ($i = 0; $i < $max; $i++) {
        // Skip empty values
        if (empty($values[$i]))
            continue;

        $result_values[] = $values[$i];
        $set_clause .= $columns[$i] . '=?, ';
    }

    // If all values are empty, return an empty query
    if (count($result_values) == 0) {
        $query = '';
    } else {
        // Remove final ', ' from SET clause
        $set_clause = substr($set_clause, 0, strlen($set_clause) - 2);

        $query = 'UPDATE ' . $table . ' SET ' . $set_clause . ' ';
    }

    return array($query, $result_values);
}

/**
 * Returns array of all categories
 *
 * @return array
 */
function get_all_categories()
{
    $result = [];
    $query = db_connect_query('SELECT * FROM categories');

    while ($row = $query->fetch_assoc())
        $result[] = $row;

    return $result;
}

function parse_selection_range($select, $column)
{
    if (empty($select))
        return '';

    $terms = explode(',', $select);
    $query = ' WHERE ';

    for ($i = 0; $i < count($terms); $i++) {
        $term = $terms[$i];
        $subquery = '';
        $subterms = explode('-', $term);

        if (count($subterms) == 1) {
            if (!is_numeric($subterms[0]))
                $subterms[0] = '`' . trim($subterms[0]) . '`';

            $subquery = $column . '=' . trim($subterms[0]);
        } elseif (count($subterms) == 2) {
            $subquery = $column . '>=' . trim($subterms[0]) . ' AND ' . $column . '<=' . trim($subterms[1]);
        }

        if ($i != 0)
            $query .= ' OR ';

        $query .= '(' . $subquery . ')';
    }

    return $query;
}