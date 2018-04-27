<?php

/**
 * Connect to MySQL database
 *
 * @return mysqli
 */
function db_connect()
{
    $db = mysqli_connect('aaroneads.com:3306', 'admin', 'Ascii32', 'cgt356_project2');
    return $db;
}

/**
 * Close connection to MySQL database
 *
 * @param mysqli $db
 */
function db_close($db)
{
    mysqli_close($db);
}

/**
 * Queries MySQL database (using prepared statements)
 *
 * @param mysqli $db
 * @param string $query
 * @param mixed $arg1
 * @param array ...$args
 * @return bool|mysqli_result
 */
function db_query($db, $query, $arg1 = null, ...$args)
{
    $arg_types = ''; // Argument types for prepared statement

    // $arg1 is defined explicitly to allow arguments to be passed as array
    // If $arg1 is not an array, prepend it to the argument list
    if (is_array($arg1)) {
        $args = $arg1;
    } else {
        array_unshift($args, $arg1);
    }

    // Build argument types
    foreach ($args as $a) {
        if (is_string($a))
            $arg_types .= 's';
        elseif (is_integer($a))
            $arg_types .= 'i';
        elseif (is_double($a))
            $arg_types .= 'd';
    }

    // bind_param must be called using call_user_func_array to allow variadic arguments
    $params[] = &$arg_types;

    for ($i = 0; $i < count($args); $i++) {
        $params[] = &$args[$i];
    }

    $stmt = $db->prepare($query);

    // Only bind params if arguments are given
    if (strlen($arg_types) > 0) {
        call_user_func_array(array($stmt, 'bind_param'), $params);
    }

    if ($stmt)
        $stmt->execute();
    else
        return false;

    // Return result, or false on error
    $result = empty($stmt->error) ? $stmt->get_result() : false;
    $stmt->close();

    return $result;
}

/**
 * Returns result of a database query, opening and closing a query link
 *
 * @param string $query
 * @param mixed $arg1
 * @param array ...$args
 * @return mixed
 */
function db_connect_query($query, $arg1 = null, ...$args)
{
    $db = db_connect();
    $params = array($db, $query, $arg1);

    foreach ($args as $a)
        $params[] = $a;

    $result = call_user_func_array('db_query', $params);
    db_close($db);

    return $result;
}

/**
 * Returns whether a row exists in the database
 *
 * @param string $table
 * @param string $field
 * @param $value
 * @return bool
 */
function row_exists($table, $field, $value)
{
    return db_connect_query('SELECT * FROM ' . $table . ' WHERE ?=?', $field, $value)->num_rows > 0;
}
